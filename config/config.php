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
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | Specify a prefix for the routes published by this package.
    | The resulting route will be something like: '.../users/posts/{id}/show/...'
    | You can define custom prefixes in the boot() of App\Providers\RouteServiceProvider
    | e.g. 'web', 'api', 'admin', etc
    |
    */
    'prefix' => 'users',

    /*
    |--------------------------------------------------------------------------
    | Route Middleware
    |--------------------------------------------------------------------------
    |
    | Specify the middleware that should protect all the routes for the posts.
    | If you're using a prefix configured in App\Providers\RouteServiceProvider,
    | then you might have configured some middleware over there already. 
    | Just modify the key to use those names.
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
];