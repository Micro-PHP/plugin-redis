<?php

namespace Micro\Plugin\Redis\Business\Redis;


use Micro\Plugin\Redis\Redis\Decorator\BaseRedisDecorator;
use Micro\Plugin\Redis\Redis\RedisInterface;

class RedisFactory implements RedisFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(): RedisInterface
    {
        $redis = new \Redis();

        return new BaseRedisDecorator($redis);
    }
}
