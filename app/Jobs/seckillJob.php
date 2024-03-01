<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Predis\Command\Traits\DB;

class seckillJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productID;
    protected $userId;
    protected $data;
    protected $seckillService;
    /**
     * Create a new job instance.
     */
    public function __construct(array $data, SeckillService $seckillService)
    {
        $this->data = $data;
        $this->seckillService = $seckillService;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 使用服务类处理秒杀请求
        $result = $this->seckillService->processSeckill(
            $this->data['user_id'],
            $this->data['product_id']
        );
        // 处理结果（例如，记录日志、发送通知等）
        // ...

        // 如果任务执行成功，不需要任何返回值
        // 如果需要，你可以返回结果或者其他数据
        // 发送通知给用户
        $this->sendNotification($this->userId, $goodsId);
    }

    protected function sendNotification($userId, $goodsId)
    {
        // 发送通知给用户，比如发送邮件、短信、APP推送等
        // 这里可以使用 Laravel 的通知系统或者其他通信手段
        // ...
    }

}
