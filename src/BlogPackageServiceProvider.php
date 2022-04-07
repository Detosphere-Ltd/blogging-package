<?php

namespace Detosphere\BlogPackage;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class BlogPackageServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'blogpackage');
	}

	protected function registerRoutes()
	{
		Route::group($this->routeConfiguration(), function () {
			$this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
		});
	}

	protected function routeConfiguration()
	{
		return [
			'prefix' => config('blogpackage.prefix'),
			'middleware' => config('blogpackage.middleware'),
		];
	}

	public function boot()
	{
		// run these if the app is booted from the console, as expected
		if ($this->app->runningInConsole()) {
			// Publish config
			$this->publishes([
				__DIR__ . '/../config/config.php' => config_path('blogpackage.php'),
			], 'config');
			// Publish assets
			$this->publishes([
				__DIR__.'/../resources/files' => storage_path('detosphere/blogpackage'),
			], 'files');

			// Export migrations
			if (!class_exists('CreatePostsTable') && !class_exists('CreateBlocksTable')) {
				$this->publishes([
					__DIR__ . '/../database/migrations/create_posts_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_posts_table.php'),
				], 'migrations');
			} else {
				// TO-DO: dump an error message to console...
				// Possibly, the user has defined those classes already.
			}

			// Export routes
			$this->registerRoutes();
		}
	}
}
