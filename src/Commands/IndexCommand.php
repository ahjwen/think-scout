<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-12 16:09
 */

namespace whereof\think\scout\Commands;


use Exception;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

/**
 * Class IndexCommand
 * @author zhiqiang
 * @package whereof\think\scout\Commands
 */
class IndexCommand extends BaseCommand
{
    protected function configure()
    {
        // 指令配置
        $this->setName('scout:index')
            ->addArgument('name', Argument::REQUIRED, 'The name of the index')
            ->addArgument('key')
            ->setDescription('Create an index');
    }

    protected function execute(Input $input, Output $output)
    {
        try {
            $name    = trim($input->getArgument('name'));
            $options = [];
            if ($key = $input->getArgument('key')) {
                $options['primaryKey'] = $key;
            }
            $this->engine()->createIndex($name, $options);
            $output->info('Index ["' . $name . '"] created successfully.');
        } catch (Exception $e) {
            $output->error($e->getMessage());
        }
    }
}