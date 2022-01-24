<?php

namespace Micro\Plugin\Redis\Configuration;

interface SslConfigurationInterface
{
    /**
     * @return bool
     */
    public function verify(): bool;

    /**
     * @return bool
     */
    public function enabled(): bool;
}
