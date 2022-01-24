<?php

namespace Micro\Plugin\Redis\Business\Redis;

interface RedisBuilderFactoryInterface
{
    /**
     * @return RedisBuilderInterface
     */
    public function createBuilder(): RedisBuilderInterface;
}
