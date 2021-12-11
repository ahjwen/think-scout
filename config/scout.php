<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Search Engine
    |--------------------------------------------------------------------------
    | Supported:  "CollectionEngine", "NullEngine"
    |
    */
    'driver'      => env('SCOUT_DRIVER', 'CollectionEngine'),
    /*
    |--------------------------------------------------------------------------
    | Soft Deletes
    |--------------------------------------------------------------------------
    |
    | This option allows to control whether to keep soft deleted records in
    | the search indexes. Maintaining soft deleted records can be useful
    | if your application still needs to search for the records later.
    |
    */
    'soft_delete' => false,
];
