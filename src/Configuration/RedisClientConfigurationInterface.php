<?php

namespace Micro\Plugin\Redis\Configuration;

interface RedisClientConfigurationInterface
{
    public const CONNECTION_TYPE_UNIX = 'unix';

    public const CONNECTION_TYPE_NET = 'network';

    /**
     * @return bool
     */
    public function reuseConnection(): bool;

    /**
     * @return string
     */
    public function name(): string;

    /**
     *
     * @return string
     */
    public function connectionType(): string;

    /**
     * @return string
     */
    public function host(): string;

    /**
     * @return int
     */
    public function port(): int;

    /**
     * @return float
     */
    public function connectionTimeout(): float;

    /**
     * @return float
     */
    public function readTimeout(): float;

    /**
     * @return int
     */
    public function retryInterval(): int;

    /**
     * @return SslConfigurationInterface
     */
    public function sslConfiguration(): SslConfigurationInterface;

    /**
     * @return AuthorizationConfigurationInterface
     */
    public function authorizationConfiguration(): AuthorizationConfigurationInterface;

    /**
     * @return ClientOptionsConfigurationInterface
     */
    public function clientOptionsConfiguration(): ClientOptionsConfigurationInterface;
}
