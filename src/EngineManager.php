<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-11 05:43
 */

namespace whereof\think\scout;

use InvalidArgumentException;
use think\helper\Arr;
use think\Manager;

/**
 * Class EngineManager
 * @author zhiqiang
 * @package whereof\think\scout
 */
class EngineManager extends Manager
{
    /**
     * @var string
     */
    protected $namespace = '\\whereof\\think\\scout\\Engines\\';

    /**
     * Get a driver instance.
     * @param string|null $name
     * @return Engine
     */
    public function engine($name = null): Engine
    {
        return $this->driver($name);
    }

    /**
     * 获取驱动类型
     * @param string $name
     * @return mixed
     */
    protected function resolveType(string $name)
    {
        return $this->getDriverConfig($name, 'driver', 'collection');
    }

    /**
     * 获取驱动配置
     * @param string $name
     * @return mixed
     */
    protected function resolveConfig(string $name)
    {
        return $this->getDriverConfig($name);
    }

    /**
     * @param $engine
     * @param string $name
     * @param null $default
     * @return mixed
     */
    public function getDriverConfig($engine, $name = null, $default = null)
    {
        if ($config = $this->getConfig("engine.{$engine}")) {
            return Arr::get($config, $name, $default);
        }
        throw new InvalidArgumentException("scout [$engine] not found.");
    }

    /**
     * @param string|null $name
     * @param null $default
     * @return mixed
     */
    public function getConfig(string $name = null, $default = null)
    {
        if (!is_null($name)) {
            return $this->app->config->get('scout.' . $name, $default);
        }
        return $this->app->config->get('scout');
    }


    /**
     * @return mixed|string|null
     */
    public function getDefaultDriver()
    {
        return $this->getConfig('default');
    }
}