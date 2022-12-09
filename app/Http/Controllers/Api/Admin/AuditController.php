<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Audit;
use App\Support\Services\PortService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class AuditController extends BaseController
{

    public function index(Request $request) {
        $limit = $request->input('limit');
        $query = Audit::query();
        $result = $query->orderByDesc('id')->paginate($limit);
        return $this->success($result);
    }


    public function store(Request $request) {
        $data = $request->all();
        Redis::sadd('gost:bypasses:bypass-0', $data['url']);
        Audit::query()->create($data);
        return $this->success();
    }


    public function destroy(Audit $audit, Request $request) {
        $audit->delete();
        Redis::srem('gost:bypasses:bypass-0', $audit->url);
        return $this->success();
    }

}
