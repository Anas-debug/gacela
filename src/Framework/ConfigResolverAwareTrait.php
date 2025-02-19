<?php

declare(strict_types=1);

namespace Gacela\Framework;

use Gacela\Framework\ClassResolver\Config\ConfigNotFoundException;
use Gacela\Framework\ClassResolver\Config\ConfigResolver;

trait ConfigResolverAwareTrait
{
    private ?AbstractConfig $config = null;

    public function getConfig(): AbstractConfig
    {
        if ($this->config === null) {
            $this->config = $this->resolveConfig();
        }

        return $this->config;
    }

    /**
     * @throws ConfigNotFoundException
     */
    private function resolveConfig(): AbstractConfig
    {
        return (new ConfigResolver())->resolve($this);
    }
}
