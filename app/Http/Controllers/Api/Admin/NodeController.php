<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Node;
use App\Models\Port;
use App\Models\User;
use App\Support\Services\GostService;
use App\Support\Services\NodeService;
use App\Support\Services\PortService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NodeController extends BaseController
{

    public function index(Request $request) {
        $limit = $request->input('limit');
        $query = Node::query();
        $result = $query->withCount('hasManyPort')->orderByDesc('id')->paginate($limit);
        return $this->success($result);
    }


    public function store(Request $request) {
        $data = $request->all();
        $data['metrics_prefix'] = $data['metrics_prefix'] ? $data['metrics_prefix'] : 'metrics';
        $data['metrics_port'] = $data['metrics_port'] ? $data['metrics_port'] : 9000;
        $data['panel_prefix'] = $data['panel_prefix'] ? $data['panel_prefix'] : 'api';
        $data['panel_port'] = $data['panel_port'] ? $data['panel_port'] : 18080;
        $data['auth'] = $data['auth'] ? $data['auth'] : 'user:pass';
        $data['multiple'] = $data['multiple'] ? $data['multiple'] : 1;
        $result = (new NodeService())->checkNodeStatus($data['ip'].':'.$data['panel_port'].'/'.$data['panel_prefix'], $data['auth']);
        if (!empty($result['code'])) {
            return $this->error($result['message']);
        }
        $node_info = Node::query()->create($data);
        if ($node_info->type == 1) {    // 中转模式
            // 创建服务节点
            (new PortService())->singleChainCreate($node_info);
            // 给用户新增服务
            $userList = User::query()->get();
            foreach ($userList as $u) {
                (new PortService())->singleServerCreate($u, $node_info);
            }
        } else {    // 直连模式
            // 给用户新增服务
            $userList = User::query()->get();
            foreach ($userList as $u) {
                (new PortService())->directlySingleServerCreate($u, $node_info);
            }
        }
        return $this->success();
    }


    public function update(Node $node, Request $request) {
        $data = $request->all();
        $original = $node::query()->find($data['id']);  // 原始数据信息
        if ($data['type'] == 1) {   // 中转节点
            // 更新用户端口信息
            if ($data['port'] != $original->port) {
                Port::query()->where('node_id', $data['id'])->update(['node_port' => $data['port']]);
                $chainName = 'chain-' . $data['id']; // 服务-通道名称
                // 分割节点-认证范围
                $node_auth = explode(':', $data['auth']);
                $c_d = [
                    'name' => $chainName,
                    'hops' => [[
                        'name' => 'hop-0',
                        'nodes' => [
                            [
                                'name' => "node-0",
                                'addr' =>  $data['ip'] . ':' . $data['port'],
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
                $result = (new GostService())->Chain('put', $c_d, $chainName);
                if (!empty($result['code'])) {
                    return $this->error($result['message']);
                }
            }
        }
        $node->update($data);
        return $this->success();
    }


    public function destroy(Node $node, Request $request) {
        // 删除用户端口信息包含服务端的服务
        $portList = Port::query()->where(['node_id'=>$node->id])->get();
        if ($node->type == 1) {
            foreach ($portList as $p) {
                $server_name = 'service-' . $p->user_id . '-' . $p->id;
                (new GostService())->Service('delete', '', $server_name);
                $p->delete();
            }
            $chainName = 'chain-' . $node['id']; // 服务-通道名称
            (new GostService())->Chain('delete', '', $chainName);
        } else {
            foreach ($portList as $p) {
//                $server_name = 'service-' . $p->user_id . '-' . $p->id;
//                (new NodeService())->Service($node, 'delete', '', $server_name);
                $p->delete();
            }
        }
        $node->delete();
        return $this->success();
    }


    public function reload($id) {
        $node_info = Node::query()->find($id);
        Port::query()->where('node_id', $id)->update(['last_dosage' => 0]); // 先清空历史流量统计，避免统计流量不正确
        (new PortService())->nodePortCoverUser($node_info);
        return $this->success();
    }

}
