<?php

return [
    /**
     * Default Search Engine
     * Supported:  "collection", "null"
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
    ],
];
