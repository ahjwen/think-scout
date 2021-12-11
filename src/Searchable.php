<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-11 05:39
 */

namespace whereof\think\scout;

use Closure;
use think\Container;
use whereof\think\scout\Support\ModelHelp;

trait Searchable
{
     /**
     * The number of models to return for pagination.
     *
     * @var int
     */
    protected $perPage = 15;
    /**
     * @param string $query
     * @param Closure $callback
     * @return Builder
     */
    public static function search($query = '', Closure $callback = null)
    {
        return Container::getInstance()->invokeClass(Builder::class, [
            'model'      => new static(),
            'query'      => $query,
            'callback'   => $callback,
            'softDelete' => static::usesSoftDelete() && config('scout.soft_delete', false),
        ]);
    }

    /**
     * @return Engine object
     */
    public function searchableUsing()
    {
        return app()->get(Engine::class)->engine();
    }

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable()
    {
        return true;
    }

    /**
     * Get the requested models from an array of object IDs.
     *
     * @param Builder $builder
     * @param array $ids
     * @return mixed
     */
    public function getScoutModelsByIds(Builder $builder, array $ids)
    {
        return $this->queryScoutModelsByIds($builder, $ids)->select();
    }

    /**
     * Get a query builder for retrieving the requested models from an array of object IDs.
     *
     * @param Builder $builder
     * @param array $ids
     * @return mixed
     */
    public function queryScoutModelsByIds(Builder $builder, array $ids)
    {
        $query = static::usesSoftDelete()
            ? $this->withTrashed() : $this->newQuery();

        if ($builder->queryCallback) {
            call_user_func($builder->queryCallback, $query);
        }
        return $query->whereIn(
            $this->getScoutKeyName(), $ids
        );
    }

    /**
     * Get the key name used to index the model.
     *
     * @return mixed
     */
    public function getScoutKeyName()
    {
        return $this->getPk();
    }

    /**
     * Get the value used to index the model.
     *
     * @return mixed
     */
    public function getScoutKey()
    {
        return $this->getKey();
    }
    /**
     * Get the number of models to return per page.
     *
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * Set the number of models to return per page.
     *
     * @param  int  $perPage
     * @return $this
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }
    /**
     * @return bool
     */
    protected static function usesSoftDelete()
    {
        return ModelHelp::isSoftDelete(get_called_class());
    }
}