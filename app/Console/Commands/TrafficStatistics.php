<?php

namespace App\Console\Commands;

use App\Models\Node;
use App\Models\Port;
use App\Models\User;
use App\Models\UserFlow;
use App\Support\Services\GostService;
use App\Support\Services\PortService;
use Carbon\Carbon;
use Illuminate\Console\Command;


class TrafficStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TrafficStatistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '实时流量统计';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $portModel = new Port();
        $userFlowModel = new UserFlow();
        $gostService = new GostService();
        while (true) {
            $total_server = 0;
            $total_dosage = 0;
            $result = $gostService->metrics();
            $isMatched = preg_match_all('/gost_service_transfer_output_bytes_total{host="localhost.localdomain",service="(.*)"}(.*)/', $result, $matches);
            if ($isMatched) {
                foreach ($matches[1] as $k => $m) {
                    $total_server += 1;
                    $name = explode('-', $m);   // port的ID和用户的ID
                    $user_id = $name[1];
                    $p_info = $portModel->query()->where([
                        'id' => $name[2],
                        'user_id' => $name[1],
                    ])->first();
                    if ($p_info) {
                        $user_yl = 0;   // 用户流量
                        $yl = (int)ceil((int)$matches[2][$k] / 1048576);    // 取整(字节 / 1048576 = MB)    流量
                        if (!$p_info->last_dosage || $p_info->last_dosage == 0) {
                            $user_yl = $yl;
                            $total_dosage += $yl;
                            $p_info->update([
                                'last_dosage' => $yl,
                                'total_dosage' => $p_info->total_dosage + $yl,
                                'last_dosage_time' => Carbon::now()->toDateTimeString(),
                            ]);
                        } else {
                            $user_yl = $yl - $p_info->last_dosage;
                            $total_dosage += $yl - $p_info->last_dosage;
                            $p_info->update([
                                'last_dosage' => $yl,
                                'total_dosage' => $p_info->total_dosage + ($yl - $p_info->last_dosage),
                                'last_dosage_time' => Carbon::now()->toDateTimeString(),
                            ]);
                        }
                        // 用户每日流量统计
                        $userFlowInfo = $userFlowModel::query()->where([
                            'user_id' => $user_id,
                            'statistical_date' => Carbon::today()->toDateTimeString(),
                        ])->first();
                        if (!$userFlowInfo) {
                            $userFlowModel::query()->create([
                                'user_id' => $user_id,
                                'total' => $user_yl,
                                'statistical_date' => Carbon::today()->toDateTimeString(),
                            ]);
                        } else {
                            $userFlowInfo->increment('total', $user_yl);
                        }
                    }
                }
            }
            echo Carbon::now()->toDateTimeString() . " >>> 统计完成 | 本次统计: " . $total_server . " 个服务 | 共 " . $total_dosage . " MB\n";
            dd();
        }
    }
}
