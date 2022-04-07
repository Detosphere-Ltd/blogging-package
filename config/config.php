<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Posts table
    |--------------------------------------------------------------------------
    |
    | The table where posts will be stored. 
    | This will be referenced by the table where blocks are stored (which defaults to 'blocks')
    |
    */
    'posts_table' => 'posts',

    /*
    |--------------------------------------------------------------------------
    | Blocks table
    |--------------------------------------------------------------------------
    |
    | The table where blocks will be stored. 
    | Every post consists of multiple blocks.
    | Each block has a specific type (e.g. image, text, video, etc.)
    |
    */
    'blocks_table' => 'blocks',

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | Specify a prefix for the routes published by this package.
    | The resulting route will be something like: '.../users/posts/{id}/show/...'
    |
    */
    'prefix' => 'users',

    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | Specify a prefix for the routes published by this package.
    | You can define custom prefixes in the boot() of App\Providers\RouteServiceProvider
    | e.g. 'web', 'api', 'admin', etc
    |
    */
    'middleware' => ['api'],

    /*
    |--------------------------------------------------------------------------
    | Configuration for Editor JS
    |--------------------------------------------------------------------------
    |
    | Specify a path to a valid JSON file that will be used as the configuration for an EditorJS instance.
    |
    */
    'editorjs_configuration' => env('BLOG_PACKAGE_EDITORJS_CONFIG', 'resources/files/editorjs-conf.json'),
    // 'editorjs_configuration' => 'resources/files/editorjs-conf.json',
];