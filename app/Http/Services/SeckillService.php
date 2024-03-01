<?php

/**
 * @file http://svn.babeltime.com/repos/C/C/trunk/card/rpcfw/module//SeckillService.php
 * @author lihongyan(lihongyan@babeltime.com)
 * @date 2024/2/27
 * @version
 * @brief
 **/

namespace App\Http\Services;

use App\Jobs\seckillJob;
use App\Models\Order;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class SeckillService
{
    protected $seckillService;

    public function __construct(SeckillService $seckillService)
    {
        $this->seckillService = $seckillService;
    }
    public function processSeckill($userId, $productId)
    {
        // 秒杀处理逻辑
        // 检查用户是否有权限参与秒杀
        // 检查商品是否存在
        // 检查库存是否足够
        // 执行秒杀操作，如减少库存、创建订单等
        $user = auth()->user();
        try {

            $lock_key = 'seckill_lock'.$productId;
            //分布式锁
            if( !Redis::set($lock_key, 1, 'NX', 'EX', 10)){
                // 获取锁失败，可能商品已被其他请求秒杀
                return;
            }

            \Illuminate\Support\Facades\DB::transaction(function () use ($productId,$user) {
                //1.库存校验
                $product = Product::find($productId);
                if( !$product || $product->stock <=0 ){
                    // 商品不存在或库存不足，秒杀失败
                    return;
                }

                //减库存
                $product->stock--;
                $product->save();

                // 生成订单
                $order = new Order();
                $order->user_id = $user->id;
                $order->product_id = $productId;
                $order->status = 'pending'; // 假设订单初始状态为待支付
                $order->save();
            });

            // 秒杀成功，分发 Job 异步处理通知逻辑
            dispatch( new seckillJob($productId,$user));
            // 释放Redis锁
            Redis::del($lock_key);
            //发布通知
            Notification::send($user,new XX);
            $this->logSeckillActivity();
        }catch (\Exception $e){
            DB::rollBack();
            Redis::del($lock_key);
            $this->logSeckillFailure($e);
        }

        // 返回处理结果
        return $result;
    }

}
