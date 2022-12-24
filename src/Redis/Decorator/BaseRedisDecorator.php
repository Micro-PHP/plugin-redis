<?php

namespace Micro\Plugin\Redis\Redis\Decorator;


use Micro\Plugin\Redis\Redis\RedisInterface;

class BaseRedisDecorator implements RedisInterface
{
    /**
     * @param RedisInterface $redis
     */
    public function __construct(private readonly \Redis $redis)
    {
    }

    /**
     * @param string $host          can be a host, or the path to a unix domain socket
     * @param int $port          optional
     * @param float $timeout       value in seconds (optional, default is 0 meaning unlimited)
     * @param string|null $persistentId  identity for the requested persistent connection
     * @param int $retryInterval retry interval in milliseconds.
     * @param float $readTimeout   value in seconds (optional, default is 0 meaning unlimited)
     *
     * @return bool
     */
    public function connect(string $host,
                            int    $port = 6379,
                            float  $timeout = 0.0,
                            string $persistentId = null,
                            int    $retryInterval = 0,
                            float  $readTimeout = 0.0): bool
    {
        return $this->redis->connect(
            $host,
            $port,
            $timeout,
            $persistentId,
            $retryInterval,
            $readTimeout,
        );
    }

    /**
     * @param string $host          can be a host, or the path to a unix domain socket
     * @param int $port          optional
     * @param float $timeout       value in seconds (optional, default is 0 meaning unlimited)
     * @param string|null $persistentId  identity for the requested persistent connection
     * @param int $retryInterval retry interval in milliseconds.
     * @param float $readTimeout   value in seconds (optional, default is 0 meaning unlimited)
     *
     * @return bool
     */
    public function pconnect(string $host,
                             int    $port = 6379,
                             float  $timeout = 0.0,
                             string $persistentId = null,
                             int    $retryInterval = 0,
                             float  $readTimeout = 0.0): bool
    {
        return $this->redis->pconnect(
            $host,
            $port,
            $timeout,
            $persistentId,
            $retryInterval,
            $readTimeout
        );
    }

    /**
     * {@inheritDoc}
     */
    public function subscribe(array $channels, array|string|callable $callback): mixed
    {
        return $this->redis->subscribe($channels, $callback);
    }

    /**
     * {@inheritDoc}
     */
    public function unsubscribe(array $channels): mixed
    {
        return $this->prepareReturnResult($this->redis->rawCommand('UNSUBSCRIBE', ''));
        //return $this->redis->unsubscribe($channels);
    }

    /**
     * {@inheritDoc}
     */
    public function publish(string $channel, string $message): int|RedisInterface
    {
        return $this->prepareReturnResult($this->redis->publish($channel, $message));
    }

    /**
     * {@inheritDoc}
     */
    public function pubsub(string $keyword, array|string $argument): array|int|RedisInterface
    {
        return $this->prepareReturnResult($this->redis->pubsub($keyword, $argument));
    }

    /**
     * {@inheritDoc}
     */
    public function setex(string $key, int $expire, mixed $value): bool|RedisInterface
    {
        return $this->prepareReturnResult($this->redis->setex($key, $expire, $value));
    }

    /**
     * {@inheritDoc}
     */
    public function setOption(int $option, mixed $value): bool
    {
        return $this->redis->setOption($option, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $key, mixed $data, int $timeout = null): bool|self
    {
        return $this->prepareReturnResult($this->redis->set($key, $data, $timeout));
    }

    /**
     * {@inheritDoc}
     */
    public function del($key1, ...$otherKeys): int|self
    {
        return $this->prepareReturnResult($this->redis->del($key1, ...$otherKeys));
    }

    /**
     * @param mixed $result
     * @return mixed
     */
    protected function prepareReturnResult(mixed $result): mixed
    {
        if(!$result) {
           return $result;
        }

        if($result instanceof \Redis) {
            return $this;
        }

        return $result;
    }

    /**
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments): mixed
    {
        if(!method_exists($this->redis, $name)) {
            throw new \BadMethodCallException(sprintf(
                'Method "%s" is not exists in the "%s"',
                $name, get_class($this->redis)
            ));
         }

        return $this->prepareReturnResult($this->redis->{$name}(...$arguments));
    }
}