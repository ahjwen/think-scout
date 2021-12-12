<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-12 16:28
 */

namespace whereof\think\scout\Commands;

use think\console\Input;
use think\console\Output;

/**
 * Class ImportCommand
 * @author zhiqiang
 * @package whereof\think\scout\Commands
 */
class ImportCommand extends BaseCommand
{
    protected function configure()
    {
        // 指令配置
        $this->setName('scout:import')
            ->setDescription('Import the given model into the search index');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln('Import the given model into the search index');
    }
}