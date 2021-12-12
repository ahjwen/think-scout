<?php

return [
    /**
     * Default Search Engine
     * Supported:  "collection", "null" "elastic"
     */
    'default'     => env('SCOUT_DRIVER', 'collection'),
    //Soft Deletes
    'soft_delete' => false,
    //engine Configuration
    'engine'      => [
        'collection' => [
            'driver' => \whereof\think\scout\Engines\CollectionEngine::class,
        ],
        'null'       => [
            'driver' => \whereof\think\scout\Engines\NullEngine::class,
        ],
        'elastic'    => [
            'driver' => \whereof\think\scout\Engines\ElasticEngine::class,
            'prefix' => '',
            //https://www.elastic.co/guide/cn/elasticsearch/php/current/_configuration.html
            'hosts'  => [
                [
                    'host'   => 'localhost',
                    'port'   => "9200",
                    'scheme' => null,
                    'user'   => null,
                    'pass'   => null,
                ],
            ],
        ]
    ],
];
