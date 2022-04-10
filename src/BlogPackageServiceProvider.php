<?php

namespace DetosphereLtd\BlogPackage;

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
		// if we only have a single prefix being set by the user as a string, then return a single set
		// but if user specifies multiple prefixes, then return multiple sets
		$prefixes = gettype(config('blogpackage.prefix'));
		$type = gettype($prefixes);

		switch ($type) {
			case 'string':
				Route::group($this->routeConfiguration(), function () {
					$this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
				});
				break;
			
			case 'array':
				foreach($prefixes as $prefix) {
					Route::group($this->routeConfiguration($prefix), function () {
						$this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
					});
				}
				break;
			
			default:
				# code...
				break;
		}
	}

	protected function routeConfiguration($prefix = null)
	{
		return [
			'prefix' => $prefix === null ? config('blogpackage.prefix') : $prefix,
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
			], 'blogging-config');
			// Publish assets
			$this->publishes([
				__DIR__.'/../resources/files' => storage_path('detosphere-ltd/blogpackage'),
			], 'blogging-files');

			// Export migrations
			if (!class_exists('CreatePostsTable') && !class_exists('CreateBlocksTable')) {
				$this->publishes([
					__DIR__ . '/../database/migrations/create_posts_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_posts_table.php'),
				], 'blogging-migrations');
			} else {
				// TO-DO: dump an error message to console...
				// Possibly, the user has defined those classes already.
			}

			// Export routes
			$this->registerRoutes();
		}
	}
}
