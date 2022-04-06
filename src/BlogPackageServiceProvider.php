<?php

namespace Detosphere\BlogPackage;

use Illuminate\Support\ServiceProvider;

class BlogPackageServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->mergeConfigFrom(__DIR__.'/../config/config.php', 'blogpackage');
	}

	public function boot()
	{
		// run these if the app is booted from the console, as expected
		if ($this->app->runningInConsole()) {
				$this->publishes([
				__DIR__.'/../config/config.php' => config_path('blogpackage.php'),
			], 'config');
            // Export migrations
            if (! class_exists('CreatePostsTable') && ! class_exists('CreateBlocksTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_posts_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_posts_table.php'),
                    __DIR__ . '/../database/migrations/create_blocks_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_blocks_table.php'),
                ], 'migrations');
            } else {
                // TO-DO: dump an error message to console...
            }
		}
	}
}