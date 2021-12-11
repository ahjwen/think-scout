<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-11 14:14
 */

namespace whereof\think\scout\Support;

use think\model\concern\SoftDelete;

/**
 * Class Model
 * @author zhiqiang
 * @package whereof\think\scout\Support
 */
class ModelHelp
{
    /**
     * @param $class
     * @return bool
     */
    public static function isSoftDelete($class)
    {
        return in_array(SoftDelete::class, class_uses_recursive($class));
    }
}