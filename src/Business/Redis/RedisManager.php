<?php

namespace Micro\Plugin\Redis\Business\Redis;

use \Redis;

class RedisManager implements RedisManagerInterface
{

    /**
     * @var array<string, Redis>
     */
    private array $redisCollection;

    /**
     * @param RedisBuilderFactoryInterface $redisBuilderFactory
     */
    public function __construct(private RedisBuilderFactoryInterface $redisBuilderFactory)
    {
        $this->redisCollection = [];
    }

    /**
     * {@inheritDoc}
     */
    public function getClient(string $clientName): Redis
    {
        if(!array_key_exists($clientName, $this->redisCollection)) {
            $this->redisCollection[$clientName] = $this->redisBuilderFactory
                ->createBuilder()
                ->create($clientName);
        }

        return $this->redisCollection[$clientName];
    }
}
