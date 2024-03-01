<?php

namespace App\Http\Controllers;

use App\Http\Services\SeckillService;
use App\Jobs\seckillJob;
use App\Jobs\SeckillRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SeckillController extends Controller
{
    protected $productID;
    protected $userId;

    public function index()
    {
        return view('seckill.index');
    }

    public function seckill(Request $request, $productId=1)
    {
        $user = auth()->user();
        $data = [$user,$productId];
        // 创建秒杀请求消息并发送到RabbitMQ队列
        dispatch(new seckillJob($data,SeckillService::class))->onQueue('seckill_queue')->onConnection('rabbitmq');
        // 返回响应给用户
        return response()->json(['message' => '秒杀请求已发送']);

        $productId = $request->input('product_id');




/*
 *redis实现
        $redis = app('redis');
        // 获取商品库存
        $stock = (int)$redis->get("product:{$productId}:stock");
        // 检查库存是否足够
        if ($stock <= 0) {
            return response()->json(['message' => '秒杀结束'], 400);
        }
        // 使用 Lua 脚本来保证原子性
        $lua = <<<'LUA'
        if redis.call('get', KEYS[1]) <= 0 then
            return 0
        end;
        local stock = redis.call('decr', KEYS[1])
        if stock >= 0 then
            return 1
        else
            return 0
        end
        LUA;

        // 执行 Lua 脚本
        $result = $redis->eval($lua, 1, "product:{$productId}:stock");

        // 检查是否秒杀成功
        if ($result === 0) {
            return response()->json(['message' => '秒杀失败'], 400);
        }

        // TODO: 生成订单、通知用户等后续操作

        return response()->json(['message' => '秒杀成功'], 200);
*/
    }
    /**
     * 记录秒杀成功日志
     */
    private function logSeckillActivity()
    {
        Log::info("User [{$this->userId}] successfully seckilled product [{$this->productId}].");
    }

    /**
     * 记录秒杀失败日志
     *
     * @param Exception $exception
     */
    private function logSeckillFailure(Exception $exception)
    {
        Log::error("User [{$this->userId}] failed to seckill product [{$this->productId}]. Reason: {$exception->getMessage()}");
    }
}
