<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-12 16:28
 */

namespace whereof\think\scout\Commands;

use Exception;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\facade\Event;
use think\Model;
use whereof\think\scout\Events\ModelsImported;

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
            ->addArgument('model', Argument::REQUIRED, 'Class name of model to bulk import')
            ->addArgument('chunk', Argument::OPTIONAL, 'The number of records to import at a time', 20)
            ->setDescription('Import the given model into the search index');
    }

    protected function execute(Input $input, Output $output)
    {
        $model = $input->getArgument('model');
        if (!$model instanceof Model && !class_exists($model)) {
            $output->error('Not Find model ' . $model);
            return;
        }
        try {
            Event::listen(ModelsImported::class, function () use (&$model, &$output) {
                $key = 1;
                $output->writeln('<comment>Imported [' . $model . '] models up to ID:</comment> ' . $key);
            });
            $model::makeAllSearchable((int)$input->getArgument('chunk'));
            Event::trigger(ModelsImported::class);
            $output->info('All [' . $model . '] records have been imported.');
        } catch (Exception $e) {
            $output->error($e->getMessage());
        }
    }
}