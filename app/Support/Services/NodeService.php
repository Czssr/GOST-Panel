<?php

namespace App\Support\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class NodeService
{



    /**
     * 获取面板流量数据
     * @return string|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function metrics($node) {
        try {
            $client = new Client();
            $request = $client->request('get', "http://{$node->ip}:{$node->metrics_port}/{$node->metrics_prefix}");
            $body = $request->getBody();
            $response = $body->getContents();
//            dd($response);
            return $response;
        } catch (ClientException $e) {
            // write log
            $response = [
                'code'=> $e->getCode(),
                'message'=> $e->getMessage(),
            ];
            echo json_encode($response);exit;
        }
    }


    /**
     * 查看/更新 配置
     * @param $method
     * @param $data
     * @return mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function webApiConfig($node, $method = 'get', $data='') {
        try {
            // 分割节点-认证范围
            $node_auth = explode(':', $node->auth);
            $url = 'http://'.$node['ip'].':'.$node['panel_port'].'/'.$node['panel_prefix'].'/config';
            $client = new Client();
            $params = [
                'auth' => [
                    $node_auth[0],
                    $node_auth[1]
                ]
            ];
            if ($method === 'get') {
                $params['query'] = $data;
            } else {
                $params['form_params'] = $data;
            }
            $request = $client->request($method, $url, $params);
            $body = $request->getBody();
            $contents = $body->getContents();
//            dd($contents);
            $response = json_decode($contents, true);
//            dd($response);
            return $response;
        } catch (ClientException $e) {
            // write log
            $response = [
                'code'=> $e->getCode(),
                'message'=> $e->getMessage(),
            ];
            echo json_encode($response);exit;
        }
    }


    /**
     * 查看/更新 节点
     * @param $method
     * @param $data
     * @return mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    /**
    {
        "name": "chain-0",
        "hops": [
            {
                "name": "hop-0",
                "nodes": [
                    {
                        "name": "node-0",
                        "addr": "192.168.50.123:8080",
                        "connector": {
                            "type": "socks5"
                        },
                        "dialer": {
                            "type": "tcp"
                        }
                    }
                ]
            }
        ]
    }
     */
    public function Chain($node, $method = 'post', $data='') {
        try {
            // 分割节点-认证范围
            $node_auth = explode(':', $node->auth);
            $url = 'http://'.$node['ip'].':'.$node['panel_port'].'/'.$node['panel_prefix'].'/config/chains';
            $client = new Client();
            $params = [
                'auth' => [
                    $node_auth[0],
                    $node_auth[1]
                ]
            ];
            $params['json'] = $data;
            $request = $client->request($method, $url, $params);
            $body = $request->getBody();
            $contents = $body->getContents();
//            dd($contents);
            $response = json_decode($contents, true);
//            dd($response);
            return $response;
        } catch (ClientException $e) {
            // write log
            $response = [
                'code'=> $e->getCode(),
                'message'=> $e->getMessage(),
            ];
            return $response;
//            echo json_encode($response);exit;
        }
    }


    /**
     * 查看/更新 服务 [一个用户-一个节点-一个服务， 多个节点 一个用户就有多个服务]
     * @param $method
     * @param $data
     * @return mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    /**
    {
        "name": "service-0",    // 服务名称
        "addr": ":7070",    // 服务端口 - 对用户的
        "bypass": "bypass-0",   // 审计名称
        "handler": {
            "type": "socks5",   // 服务接口类型
            "chain": "chain-0"  // 节点名称
        },
        "listener": {
            "type": "tcp"
        }
    }
     */
    public function Service($node, $method = 'post', $data='', $server_name='') {
        try {
            // 分割节点-认证范围
            $node_auth = explode(':', $node->auth);
            $url = 'http://'.$node['ip'].':'.$node['panel_port'].'/'.$node['panel_prefix'].'/config/services';
            $client = new Client();
            $params = [
                'auth' => [
                    $node_auth[0],
                    $node_auth[1]
                ]
            ];
            if ($method == 'delete') {
                $request = $client->request($method, $url.'/'.$server_name, $params);
            } elseif ($method == 'put') {
                $params['json'] = $data;
                $request = $client->request($method, $url.'/'.$server_name, $params);
            } else {
                $params['json'] = $data;
                $request = $client->request($method, $url, $params);
            }
            $body = $request->getBody();
            $contents = $body->getContents();
//            dd($contents);
            $response = json_decode($contents, true);
//            dd($response);
            return $response;
        } catch (ClientException $e) {
            // write log
            $response = [
                'code'=> $e->getCode(),
                'message'=> $e->getMessage(),
            ];
            return $response;
//            echo json_encode($response);exit;
        }
    }


    /**
     * 检测节点是否正确可用
     * @param $url
     * @param $auth
     * @return mixed|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkNodeStatus($url='', $auth='') {
        try {
            $auth = explode(':', $auth);
            $url = 'http://'.$url.'/config';
            $client = new Client();
            $params = [
                'auth' => [
                    $auth[0],
                    $auth[1]
                ],
//                'timeout' => 10
            ];
            $request = $client->request('get', $url, $params);
            $body = $request->getBody();
            $contents = $body->getContents();
            $response = json_decode($contents, true);
            return $response;
        } catch (ClientException $e) {
            $response = [
                'code'=> $e->getCode(),
                'message'=> $e->getMessage(),
            ];
//            echo json_encode($response);exit;
            return $response;
        }
    }
}
