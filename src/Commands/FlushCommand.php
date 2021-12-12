<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-12 16:25
 */

namespace whereof\think\scout\Commands;

use Exception;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\Model;

class FlushCommand extends BaseCommand
{
    protected function configure()
    {
        // 指令配置
        $this->setName('scout:flush')
            ->addArgument('model', Argument::REQUIRED, 'model class')
            ->setDescription("Flush all of the model's records from the index");
    }

    protected function execute(Input $input, Output $output)
    {
        $model = $input->getArgument('model');

        if (!$model instanceof Model && !class_exists($model)) {
            $output->error('Not Find model ' . $model);
            return;
        }
        try {
            $model::removeAllFromSearch();
            $output->info('All [' . $model . '] records have been flushed.');
        } catch (Exception $e) {
            $output->error($e->getMessage());
        }
    }
}