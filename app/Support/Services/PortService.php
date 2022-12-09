<?php

namespace App\Support\Services;

use App\Models\Node;
use App\Models\Port;
use App\User;

class PortService
{

    /**
     * 创建新用户节点服务对应端口
     * @param $user [用户信息]
     * @return void
     */
    public function serverPortCreateUser($user) {
        $nodeList = Node::query()->where([['status', '=', 1]])->get();
        foreach ($nodeList as $n) {
            if ($n->type === 1) {
                self::singleServerCreate($user, $n);
            } else {
                self::directlySingleServerCreate($user, $n);
            }
        }
    }


    /**
     * 服务端重启脚本 覆盖线上配置到中转服务端
     * @return void
     */
    public function serverPortCoverUser() {
        $userModel = new User();
        $portModel = new Port();
        $nodeModel = new Node();

        $nodeList = $nodeModel->query()->where(['type'=>1, 'status'=>1])->get();
        foreach ($nodeList as $n) {
            self::singleChainCreate($n);
            $portList = $portModel->query()->where(['node_id'=>$n->id])->get();
            foreach ($portList as $v) {
                $userInfo = $userModel->query()->find($v->user_id);
                self::singleServerCreate($userInfo, $n);
            }
        }
    }

    /**
     * 服务端重启脚本 覆盖线上配置到节点服务端
     * @return void
     */
    public function nodePortCoverUser($node='') {
        $userModel = new User();
        $portModel = new Port();
        $nodeModel = new Node();
        if ($node) {
            $portList = $portModel->query()->where(['node_id'=>$node->id])->get();
            foreach ($portList as $v) {
                $userInfo = $userModel->query()->find($v->user_id);
                self::directlySingleServerCreate($userInfo, $node);
            }
        } else {
            $nodeList = $nodeModel->query()->where(['type'=>2, 'status'=>1])->get();
            foreach ($nodeList as $n) {
                $portList = $portModel->query()->where(['node_id'=>$n->id])->get();
                foreach ($portList as $v) {
                    $userInfo = $userModel->query()->find($v->user_id);
                    self::directlySingleServerCreate($userInfo, $n);
                }
            }
        }
    }


    /**
     * 创建中转单个节点
     * @param $node
     * @return void
     */
    public function singleChainCreate($node) {
        // 新增节点 START
        $chainName = 'chain-' . $node['id']; // 服务-通道名称
        // 分割节点-认证范围
        $node_auth = explode(':', $node['auth']);
        $c_d = [
            'name' => $chainName,
            'hops' => [[
                'name' => 'hop-0',
                'nodes' => [
                    [
                        'name' => "node-0",
                        'addr' =>  $node['ip'] . ':' . $node['port'],
                        'connector' => [
                            'type' => 'socks5',
                            'auth' => [
                                'username' => $node_auth[0],
                                'password' => $node_auth[1]
                            ]
                        ],
                        'dialer' => [
                            'type' => 'tcp'
                        ]
                    ]
                ],
            ]]
        ];
        (new GostService())->Chain('post', $c_d);
    }

    /**
     * 创建中转单个服务
     * @param $user [用户信息]
     * @param $node [节点信息]
     * @return void
     */
    public function singleServerCreate($user, $node) {
        $GostService = new GostService();
        $portModel = new Port();
        $user_id = $user->id;
        $user_auth = explode(':', $user->auth);
        // 先检查用户是否已经有这个节点了
        $is_user_port = $portModel->query()->where([
            ['user_id', '=', $user_id],
            ['node_id', '=', $node->id]
        ])->first();
        // 如果存在则跳出
        if ($is_user_port) {
            $chainName = 'chain-' . $node->id; // 服务-通道名称
            // 创建服务-转发服务
            $GostService->Service('post', [
                'name' => 'service-' . $user_id . '-' . $is_user_port->id,
                'addr' => ':' . $is_user_port->server_port,
                'bypass' =>  'bypass-0',    // 审计名称
                'handler' => [
                    'type' => 'socks5',
                    'chain' => $chainName,
                    'auth' => [
                        'username' => $user_auth[0],
                        'password' => $user_auth[1]
                    ]
                ],
                'listener' => [
                    'type' => 'tcp'
                ]
            ]);
        } else {
            // 节点-端口
            $node_port = $node->port;
            // 分割服务-端口范围
            $server_port_range = explode('-', env('GOST_SERVER_PORT'));
            $server_port = null; // 服务端口
            // 检测服务-端口是否被占用
            for ($s=$server_port_range[0]; $s<=$server_port_range[1]; $s++) {
                $is_sever_occupy = $portModel->query()->where('server_port', $s)->first();
                if (!$is_sever_occupy) {
                    $server_port = $s;
                    break;
                }
            }
            // 创建服务-端口数据
            $p_res = $portModel->create([
                'user_id' => $user_id,
                'node_id' => $node->id,
                'node_port' => $node_port,
                'server_port' => $server_port,
            ]);
            $chainName = 'chain-' . $node->id; // 服务-通道名称
            // 创建服务-转发服务
            $GostService->Service('post', [
                'name' => 'service-' . $user_id . '-' . $p_res->id,
                'addr' => ':' . $server_port,
                'bypass' =>  'bypass-0',    // 审计名称
                'handler' => [
                    'type' => 'socks5',
                    'chain' => $chainName,
                    'auth' => [
                        'username' => $user_auth[0],
                        'password' => $user_auth[1]
                    ]
                ],
                'listener' => [
                    'type' => 'tcp'
                ]
            ]);
        }

    }


    /**
     * 创建直连单个服务
     * @param $user [用户信息]
     * @param $node [节点信息]
     * @return void
     */
    public function directlySingleServerCreate($user, $node) {
        $nodeService = new NodeService();
        $portModel = new Port();
        $user_id = $user->id;
        $user_auth = explode(':', $user->auth);
        // 先检查用户是否已经有这个节点了
        $is_user_port = $portModel->query()->where([
            ['user_id', '=', $user_id],
            ['node_id', '=', $node->id]
        ])->first();
        // 如果存在则跳出
        if ($is_user_port) {
            // 创建服务
            $nodeService->Service($node, 'post', [
                'name' => 'service-' . $user_id . '-' . $is_user_port->id,
                'addr' => ':' . $is_user_port->server_port,
                'bypass' =>  'bypass-0',    // 审计名称
                'handler' => [
                    'type' => 'socks5',
                    'auth' => [
                        'username' => $user_auth[0],
                        'password' => $user_auth[1]
                    ]
                ],
                'listener' => [
                    'type' => 'tcp'
                ]
            ]);
        } else {
            // 分割节点-端口范围
            $node_port_range = explode('-', $node->port);
            $node_port = null; // 服务端口
            // 检测服务-端口是否被占用
            for ($s=$node_port_range[0]; $s<=$node_port_range[1]; $s++) {
                $is_node_occupy = $portModel->query()->where([
                    'node_id' => $node->id,
                    'node_port' => $s,
                ])->first();
                if (!$is_node_occupy) {
                    $node_port = $s;
                    break;
                }
            }
            // 创建节点-端口数据
            $p_res = $portModel->create([
                'user_id' => $user_id,
                'node_id' => $node->id,
                'node_port' => $node_port,
                'server_port' => $node_port,
            ]);
            // 创建服务
            $nodeService->Service($node, 'post', [
                'name' => 'service-' . $user_id . '-' . $p_res->id,
                'addr' => ':' . $node_port,
                'bypass' =>  'bypass-0',    // 审计名称
                'handler' => [
                    'type' => 'socks5',
                    'auth' => [
                        'username' => $user_auth[0],
                        'password' => $user_auth[1]
                    ]
                ],
                'listener' => [
                    'type' => 'tcp'
                ]
            ]);
        }

    }
}
