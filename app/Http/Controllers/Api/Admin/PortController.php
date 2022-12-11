<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Node;
use App\Models\Port;
use Illuminate\Http\Request;

class PortController extends BaseController
{

    /**
     * Notes:
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request) {
        $limit = $request->input('limit');
        $user_id = $request->input('user_id');
        $query = Port::query();
        if ($user_id) {
            $query->where('user_id', $user_id);
        }
        $result = $query->with('hasOneNode')->orderByDesc('id')->paginate($limit);
        return $this->success($result);
    }


    /**
     * Notes:
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request) {
        $data = $request->all();
        $node_info = Port::query()->create($data);
        return $this->success($node_info);
    }


    /**
     * Notes:
     * @param Port $port
     * @param Request $request
     * @return mixed
     */
    public function update(Port $port, Request $request) {
        $data = $request->all();
        $port->update($data);
        return $this->success();
    }


    /**
     * Notes:
     * @param Port $port
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function destroy(Port $port, Request $request) {
        $port->delete();
        return $this->success();
    }

}
