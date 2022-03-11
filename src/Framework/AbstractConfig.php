<?php

declare(strict_types=1);

namespace Gacela\Framework;

use Gacela\Framework\Exception\ConfigException;

abstract class AbstractConfig
{
    /**
     * @param null|mixed $default
     *
     * @throws ConfigException
     *
     * @return mixed
     */
    protected function get(string $key, $default = Config::DEFAULT_CONFIG_VALUE)
    {
        return Config::getInstance()->get($key, $default);
    }

	/**
	 *
	 * Allow easy access the root dir of the project from the Factory
	 *
	 * @return string
	 */

    public function getAppRootDir(): string
    {
        return Config::getInstance()->getAppRootDir();
    }
}
