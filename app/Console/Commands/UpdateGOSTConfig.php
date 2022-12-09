<?php

namespace App\Console\Commands;

use App\Models\Node;
use App\Models\Port;
use App\Models\User;
use App\Support\Services\GostService;
use App\Support\Services\PortService;
use Illuminate\Console\Command;


class UpdateGOSTConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UpdateGOSTConfig';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '特殊情况，重新覆盖GOST的配置文件 比如重启了服务端';

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
        //  【重要】 先清空历史流量统计，避免统计流量不正确
        Port::query()->update(['last_dosage' => 0]);

        (new PortService())->serverPortCoverUser();
    }
}
