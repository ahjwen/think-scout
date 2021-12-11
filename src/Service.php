<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-11 05:35
 */

namespace whereof\think\scout;

/**
 * Class Service
 * @author zhiqiang
 * @package whereof\think\scout
 */
class Service extends \think\Service
{
    public function register()
    {
        $this->app->bind(Engine::class, function () {
            return new EngineManager($this->app);
        });
    }
}