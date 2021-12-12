<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-11 05:39
 */

namespace whereof\think\scout;

use Closure;
use think\Container;
use think\model\Collection;
use whereof\think\scout\Support\ModelHelp;

/**
 * @author zhiqiang
 * Trait Searchable
 * @package whereof\think\scout
 */
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
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return $this->getTable();
    }

    /**
     * Get the indexable data array for the model.
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->toArray();
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
     * @param int $perPage
     * @return $this
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
        return $this;
    }

    /**
     * Remove all instances of the model from the search index.
     *
     * @return void
     */
    public static function removeAllFromSearch()
    {
        $self = new static;
        $self->searchableUsing()->flush($self);
    }

    /**
     * 全部数据 $query = $self->withTrashed();
     *
     * 只查询没有删除的数据 $query
     *
     * 查询删除的数据  $self->onlyTrashed()
     *
     * @param int|null $chunk
     * @return void
     */
    public static function makeAllSearchable(int $chunk = null)
    {
        $self       = new static;
        $softDelete = static::usesSoftDelete() && config('scout.soft_delete', false);
        if (!$softDelete) {
            $newQuery = $self->withTrashed();
        } else {
            $newQuery = $self->query();
        }
        $newQuery->when(true, function ($query) use (&$self) {
            $self->makeAllSearchableUsing($query);
        })->order($self->getPk());//->chunk($chunk, function (Collection $model) use (&$self) {
//            $self->searchableUsing()->update($model);
//        });
    }


    /**
     * @param \think\db\Builder $query
     * @return \think\db\Builder
     */
    protected function makeAllSearchableUsing($query)
    {
        return $query;
    }


    /**
     * @return Engine
     */
    public function searchableUsing()
    {
        return app()->get(Engine::class)->engine();
    }

    /**
     * @return bool
     */
    protected static function usesSoftDelete()
    {
        return ModelHelp::isSoftDelete(get_called_class());
    }
}