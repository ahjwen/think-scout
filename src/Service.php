<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-11 05:35
 */

namespace whereof\think\scout;

use whereof\think\scout\Commands\DeleteIndexCommand;
use whereof\think\scout\Commands\FlushCommand;
use whereof\think\scout\Commands\ImportCommand;
use whereof\think\scout\Commands\IndexCommand;

/**
 * Class Service
 * @author zhiqiang
 * @package whereof\think\scout
 */
class Service extends \think\Service
{
    public function boot()
    {
        $this->commands([
            IndexCommand::class,
            DeleteIndexCommand::class,
            ImportCommand::class,
            FlushCommand::class,

        ]);
    }

    public function register()
    {
        $this->app->bind(Engine::class, function () {
            return new EngineManager($this->app);
        });
    }
}