<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-11 05:58
 */

namespace whereof\think\scout\Engines;

use think\Collection;
use think\db\BaseQuery;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\helper\Arr;
use think\Model;
use whereof\think\scout\Builder;
use whereof\think\scout\Engine;
use whereof\think\scout\Support\ModelHelp;

class CollectionEngine extends Engine
{

    /**
     * Update the given model in the index.
     * @param Collection $models
     * @return void
     */
    public function update(Collection $models): void
    {
        // TODO: Implement update() method.
    }

    /**
     * Remove the given model from the index.
     * @param Collection $models
     * @return void
     */
    public function delete(Collection $models): void
    {
        // TODO: Implement delete() method.
    }

    /**
     * Perform the given search on the engine.
     * @param Builder $builder
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function search(Builder $builder)
    {
        $models = $this->searchModels($builder);
        return [
            'results' => $models->all(),
            'total'   => count($models),
        ];
    }

    /**
     * Perform the given search on the engine.
     * @param Builder $builder
     * @param int $perPage
     * @param int $page
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function paginate(Builder $builder, int $perPage, int $page)
    {
        $models = $this->searchModels($builder);
        $offset = max(0, ($page - 1) * $perPage);
        return [
            'results' => $models->slice($offset, $perPage)->all(),
            'total'   => count($models),
        ];
    }

    /**
     * Pluck and return the primary keys of the given results.
     * @param mixed $results
     * @return Collection
     */
    public function mapIds($results): Collection
    {
        $results = $results['results'];
        return count($results) > 0 ?
            collect(Arr::pluck($results, $results[0]->getPk()))->values()
            : collect();
    }

    /**
     * Map the given results to instances of the given model.
     * @param Builder $builder
     * @param mixed $results
     * @param Model $model
     * @return Collection
     */
    public function map(Builder $builder, $results, Model $model): Collection
    {
        $results = $results['results'];
        if (count($results) === 0) {
            return collect($model);
        }
        $objectIds         = collect(Arr::pluck($results, $model->getPk()))->values()->all();
        $objectIdPositions = array_flip($objectIds);
        return $model->getScoutModelsByIds(
            $builder, $objectIds
        )->filter(function ($model) use ($objectIds) {
            return in_array($model->getScoutKey(), $objectIds);
        })->sort(function ($model) use ($objectIdPositions) {
            return $objectIdPositions[$model->getScoutKey()];
        })->values();
    }

    /**
     * Get the total count from a raw result returned by the engine.
     * @param mixed $results
     * @return int
     */
    public function getTotalCount($results): int
    {
        return $results['total'];
    }

    /**
     * Flush all of the model's records from the engine.
     * @param Model $model
     */
    public function flush(Model $model): void
    {
        // TODO: Implement flush() method.
    }


    /**
     * Create a search index.
     * @param $name
     * @param array $options
     * @return mixed
     */
    public function createIndex($name, array $options = [])
    {
        // TODO: Implement createIndex() method.
    }

    /**
     * Delete a search index.
     *
     * @param string $name
     * @return mixed
     */
    public function deleteIndex($name)
    {
        // TODO: Implement deleteIndex() method.
    }

    /**
     * @param Builder $builder
     * @return Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    protected function searchModels(Builder $builder)
    {
        $model = $this->ensureSoftDeletesAreHandled($builder);

        $model->when(!is_null($builder->callback), function (BaseQuery $query) use (&$builder) {
            call_user_func($builder->callback, $query, $builder, $builder->query);
        })->when(!$builder->callback && count($builder->wheres) > 0, function (BaseQuery $query) use ($builder) {
            foreach ($builder->wheres as $key => $value) {
                if ($key !== '__soft_deleted') {
                    $query->where($key, $value);
                }
            }
        })->when(!$builder->callback && count($builder->whereIns) > 0, function (BaseQuery $query) use ($builder) {
            foreach ($builder->whereIns as $key => $values) {
                $query->whereIn($key, $values);
            }
        })->order($builder->model->getPk(), 'desc');
        //exit($query->buildSql());
        return $model->select()->filter(function (Model $model) use ($builder) {
            if (!$builder->model->shouldBeSearchable()) {
                return false;
            }
            if (!$builder->query) {
                return true;
            }
            return false;
        })->values();
    }


    /**
     * @param Builder $builder
     * @return BaseQuery
     */
    protected function ensureSoftDeletesAreHandled(Builder $builder)
    {
        if (Arr::get($builder->wheres, '__soft_deleted') === 0) {
            return $builder->model->newQuery();
        } elseif (Arr::get($builder->wheres, '__soft_deleted') === 1) {
            return $builder->model->onlyTrashed();
        } elseif (ModelHelp::isSoftDelete($builder->model) && config('scout.soft_delete', false)) {
            return $builder->model->newQuery();
        }
        return $builder->model->newQuery();
    }

}