<?php

namespace Micro\Plugin\Redis\Business\Redis;

class RedisFactory implements RedisFactoryInterface
{
    /**
     * @return \Redis
     */
    public function create(): \Redis
    {
        return new \Redis();
    }
}
