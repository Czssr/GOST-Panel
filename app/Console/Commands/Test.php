<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Support\Services\GostService;
use App\Support\Services\PortService;
use Illuminate\Console\Command;


class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $pd= 1;
        $pi= 1;
        $ab = "service-{$pd}-{$pi}";

        dd($ab);
//        $s = 'gost_service_transfer_output_bytes_total{host="localhost.localdomain",service="service-1-9"} 5.58045199e+08\ngost_service_transfer_output_bytes_total{host="localhost.localdomain",service="service-1-9"} 5.58045199e+08\n';
//        preg_match_all('/gost_service_transfer_output_bytes_total{host="localhost.localdomain",service="(.*)"}(.*)\\\\n/', $s, $matches);
//        dd($matches);
        //        $yl = (int)ceil((int)$matches[2][0] / 1048576);    // 取整(字节 / 1048576 = MB)
//        dd($yl);


//        $userInfo = User::query()->find(1);
//        (new PortService)->serverPortCreateUser($userInfo);

        $gostService = new GostService();
        $result = $gostService->metrics();
        $isMatched = preg_match_all('/gost_service_transfer_output_bytes_total{host="localhost.localdomain",service="(.*)"}(.*)/', $result, $matches);
        if ($isMatched) {
            foreach ($matches[1] as $k => $m) {
                $yl = (int)ceil((int)$matches[2][$k] / 1048576);    // 取整(字节 / 1048576 = MB)
                var_dump($m, $yl);
            }
        }
        dd();


                var_dump($isMatched, $matches);
//        dd();
//        dd($result[2]);

    }
}
