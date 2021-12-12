<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-12 20:28
 */

namespace whereof\think\scout\Events;

use think\Model;

/**
 * Class ModelsFlushed
 * @author zhiqiang
 * @package whereof\think\scout\Events
 */
class ModelsFlushed
{
    /**
     * @var Model
     */
    public $models;

    /**
     * ModelsImported constructor.
     * @param Model $models
     */
    public function __construct(Model $models)
    {
        $this->models = $models;
    }
}