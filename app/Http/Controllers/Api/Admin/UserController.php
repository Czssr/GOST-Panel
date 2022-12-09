<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Support\Services\PortService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{

    public function index(Request $request) {
        $user_id = $request->input('user_id');
        $status = $request->input('status');
        $limit = $request->input('limit');
        $query = User::query();
        if ($user_id) $query->where('id', $user_id);
        if ($status) $query->where('status', $status);
        $query->withCount([
            'hasManyFlow as flow_sum' => function($query) {
                $query->where([
                   ['statistical_date', '>=', Carbon::now()->firstOfMonth()->toDateTimeString()],
                   ['statistical_date', '<=', Carbon::now()->lastOfMonth()->toDateTimeString()],
                ])->select(DB::raw('SUM(total)'));
            }
        ]);
        $result = $query->orderByDesc('id')->paginate($limit);
        return $this->success($result);
    }


    public function store(Request $request) {
        $data = $request->all();
        $data['password'] = Hash::make($data['password']);
        $data['auth'] = random_generation() . ':' . random_generation();
        $userInfo = User::query()->create($data);
        // 创建用户节点服务对应端口
        (new PortService)->serverPortCreateUser($userInfo);
        return $this->success();
    }


    public function update(User $user, Request $request) {
        $data = $request->all();
        $user->update($data);
        return $this->success();
    }


    public function destroy(User $user, Request $request) {
        $user->delete();
        return $this->success();
    }

}
