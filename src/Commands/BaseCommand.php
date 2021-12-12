<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-12 16:15
 */

namespace whereof\think\scout\Commands;

use think\console\Command;
use whereof\think\scout\Engine;

/**
 * Class BaseCommand
 * @author zhiqiang
 * @package whereof\think\scout\Commands
 */
class BaseCommand extends Command
{
    /**
     * @return Engine
     */
    public function engine()
    {
        return $this->app->get(Engine::class)->engine();
    }
}