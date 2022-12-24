<?php

namespace Micro\Plugin\Redis\Business\Redis;

use Micro\Plugin\Redis\Redis\RedisInterface;

class RedisManager implements RedisManagerInterface
{
    /**
     * @var array<string, RedisInterface>
     */
    private array $redisCollection;

    /**
     * @param RedisBuilderFactoryInterface $redisBuilderFactory
     */
    public function __construct(private readonly RedisBuilderFactoryInterface $redisBuilderFactory)
    {
        $this->redisCollection = [];
    }

    /**
     * {@inheritDoc}
     */
    public function getClient(string $clientName): RedisInterface
    {
        if(!array_key_exists($clientName, $this->redisCollection)) {
            $this->redisCollection[$clientName] = $this->redisBuilderFactory
                ->createBuilder()
                ->create($clientName);
        }

        return $this->redisCollection[$clientName];
    }
}
