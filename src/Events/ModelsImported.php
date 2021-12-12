<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-12 20:20
 */

namespace whereof\think\scout\Events;

use think\Model;

/**
 * Class ModelsImported
 * @author zhiqiang
 * @package whereof\think\scout\Events
 */
class ModelsImported
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