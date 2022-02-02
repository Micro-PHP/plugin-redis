<?php

namespace Micro\Plugin\Redis\Configuration;

interface AuthorizationConfigurationInterface
{
    /**
     * @return string|null
     */
    public function username(): ?string;

    /**
     * @return string|null
     */
    public function password(): ?string;
}
