<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-12 16:20
 */

namespace whereof\think\scout\Commands;

use Exception;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

/**
 * Class DeleteIndexCommand
 * @author zhiqiang
 * @package whereof\think\scout\Commands
 */
class DeleteIndexCommand extends BaseCommand
{
    protected function configure()
    {
        // 指令配置
        $this->setName('scout:delete-index')
            ->addArgument('name', Argument::REQUIRED, 'The name of the index')
            ->setDescription('Delete an index');
    }

    protected function execute(Input $input, Output $output)
    {
        try {
            $name = trim($input->getArgument('name'));
            $this->engine()->deleteIndex($name);
            $output->info('Index ["' . $name . '"] deleted successfully.');
        } catch (Exception $e) {
            $output->error($e->getMessage());
        }
    }
}