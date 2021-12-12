<?php
/*
 * Desc: 
 * User: zhiqiang
 * Date: 2021-12-12 12:51
 */

namespace whereof\think\scout\Engines;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use think\Collection;
use think\helper\Arr;
use think\Model;
use whereof\think\scout\Builder;
use whereof\think\scout\Engine;

/**
 * Class ElasticEngine
 * @author zhiqiang
 * @package whereof\think\scout\Engines
 */
class ElasticEngine extends Engine
{

    /**
     * elastic Correspondence between parameters and functions
     * @var array
     */
    public static $configMappings = [
        'sslVerification'    => 'setSSLVerification',
        'sniffOnStart'       => 'setSniffOnStart',
        'retries'            => 'setRetries',
        'httpHandler'        => 'setHandler',
        'connectionPool'     => 'setConnectionPool',
        'connectionSelector' => 'setSelector',
        'serializer'         => 'setSerializer',
        'connectionFactory'  => 'setConnectionFactory',
        'endpoint'           => 'setEndpoint',
        'namespaces'         => 'registerNamespace',
    ];
    /**
     * @var Client
     */
    protected $elastic;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->elastic = $this->elastic($this->config);
    }

    /**
     * @param $config
     * @return Client
     */
    public function elastic($config)
    {
        $clientBuilder = ClientBuilder::create();
        $clientBuilder->setHosts($config['hosts']);
        // Set additional client configuration
        foreach (self::$configMappings as $key => $method) {
            $value = Arr::get($config, $key);
            if (is_array($value)) {
                foreach ($value as $vItem) {
                    $clientBuilder->$method($vItem);
                }
            } elseif ($value !== null) {
                $clientBuilder->$method($value);
            }
        }
        return $clientBuilder->build();
    }

    /**
     * Update the given model in the index.
     * @param Collection $models
     * @return void
     */
    public function update(Collection $models): void
    {
        if (empty($models)) {
            return;
        }
        $params['body'] = [];
        $models->each(function ($model) use (&$params) {
            $params['body'][] = [
                'update' => [
                    '_id'    => $model->getKey(),
                    '_index' => $this->buildElasticIndex($model),
                    '_type'  => get_class($model),
                ]
            ];
            $params['body'][] = [
                'doc'           => $model->toSearchableArray(),
                'doc_as_upsert' => true
            ];
        });
        $this->elastic->bulk($params);
    }

    /**
     * Remove the given model from the index.
     * @param Collection $models
     * @return void
     */
    public function delete(Collection $models): void
    {
        $params['body'] = [];
        $models->each(function ($model) use (&$params) {
            $params['body'][] = [
                'delete' => [
                    '_id'    => $model->getKey(),
                    '_index' => $this->buildElasticIndex($model),
                    '_type'  => get_class($model),
                ]
            ];
        });
        $this->elastic->bulk($params);
    }

    /**
     * Perform the given search on the engine.
     * @param Builder $builder
     * @return array
     */
    public function search(Builder $builder)
    {

    }

    /**
     * Perform the given search on the engine.
     * @param Builder $builder
     * @param int $perPage
     * @param int $page
     * @return array
     */
    public function paginate(Builder $builder, int $perPage, int $page)
    {
        // TODO: Implement paginate() method.
    }

    /**
     * Pluck and return the primary keys of the given results.
     * @param mixed $results
     * @return Collection
     */
    public function mapIds($results): Collection
    {
        // TODO: Implement mapIds() method.
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
    }

    /**
     * Get the total count from a raw result returned by the engine.
     * @param mixed $results
     * @return int
     */
    public function getTotalCount($results): int
    {
        // TODO: Implement getTotalCount() method.
    }

    /**
     * Flush all of the model's records from the engine.
     * @param Model $model
     * @return void
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
     * @param Model $model
     * @return string
     */
    protected function buildElasticIndex(Model $model)
    {
        return ($this->config['prefix'] ?: '') . $model->searchableAs();
    }
}