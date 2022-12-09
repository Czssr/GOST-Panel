<?php

namespace App\Support\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class GostService
{


    /**
     * 获取面板流量数据
     * @return string|void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function metrics() {
        try {
            $client = new Client();
            $request = $client->request('get', env('GOST_METRICS'));
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
    public function webApiConfig($method = 'get', $data='') {
        try {
            $client = new Client();
            $params = [
                'auth' => [
                    env('GOST_Web_API_USER'),
                    env('GOST_Web_API_PASS')
                ]
            ];
            if ($method === 'get') {
                $params['query'] = $data;
            } else {
                $params['form_params'] = $data;
            }
            $request = $client->request($method, env('GOST_Web_API').'/config', $params);
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
    public function Chain($method = 'post', $data='', $chain_name='') {
        try {
            $client = new Client();
            $params = [
                'auth' => [
                    env('GOST_Web_API_USER'),
                    env('GOST_Web_API_PASS')
                ]
            ];
            if ($method == 'delete') {
                $request = $client->request($method, env('GOST_Web_API').'/config/chains/'.$chain_name, $params);
                $body = $request->getBody();
                $contents = $body->getContents();
            } elseif ($method == 'put') {
                $params['json'] = $data;
                $request = $client->request($method, env('GOST_Web_API').'/config/chains/'.$chain_name, $params);
                $body = $request->getBody();
                $contents = $body->getContents();
            } else {
                $params['json'] = $data;
                $request = $client->request($method, env('GOST_Web_API') . '/config/chains', $params);
                $body = $request->getBody();
                $contents = $body->getContents();
            }
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
    public function Service($method = 'post', $data='', $server_name='') {
        try {
            $client = new Client();
            $params = [
                'auth' => [
                    env('GOST_Web_API_USER'),
                    env('GOST_Web_API_PASS')
                ]
            ];
            if ($method == 'delete') {
                $request = $client->request($method, env('GOST_Web_API').'/config/services/'.$server_name, $params);
                $body = $request->getBody();
                $contents = $body->getContents();
            } elseif ($method == 'put') {
                $params['json'] = $data;
                $request = $client->request($method, env('GOST_Web_API').'/config/services/'.$server_name, $params);
                $body = $request->getBody();
                $contents = $body->getContents();
            } else {
                $params['json'] = $data;
                $request = $client->request($method, env('GOST_Web_API').'/config/services', $params);
                $body = $request->getBody();
                $contents = $body->getContents();
            }
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

}
