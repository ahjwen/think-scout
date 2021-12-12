<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-12 16:25
 */

namespace whereof\think\scout\Commands;

use think\console\Input;
use think\console\Output;

class FlushCommand extends BaseCommand
{
    protected function configure()
    {
        // 指令配置
        $this->setName('scout:flush')
            ->setDescription("Flush all of the model's records from the index");
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln("Flush all of the model's records from the index");
    }
}