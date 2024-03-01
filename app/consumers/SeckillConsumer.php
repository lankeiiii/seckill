<?php

/**
 * @file http://svn.babeltime.com/repos/C/C/trunk/card/rpcfw/module//SeckillConsumer.php
 * @author lihongyan(lihongyan@babeltime.com)
 * @date 2024/2/27
 * @version
 * @brief
 **/


namespace App\consumers;

use Illuminate\Queue\Consumer;
use Illuminate\Queue\Job;

class SeckillConsumer
{
    public function consume(Consumer $consumer, Job $job, array $data)
    {
        // 处理任务
        // 这里不需要再分发任务，因为任务已经在秒杀控制器中分发过了

        // 任务处理完成后，删除该任务
        $job->delete();
    }
}
