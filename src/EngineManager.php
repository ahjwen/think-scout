<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-11 05:43
 */

namespace whereof\think\scout;

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
    public function engine($name = null):Engine
    {
        return $this->driver($name);
    }

    /**
     * 默认驱动
     * @return string|null
     */
    public function getDefaultDriver()
    {
        if (is_null($driver = config('scout.driver'))) {
            return 'null';
        }
        return $driver;
    }
}