<?php

namespace DetosphereLtd\BlogPackage\Tests;

use DetosphereLtd\BlogPackage\BlogPackageServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Often used to instantiate a model in all following tests
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    /**
     * Used for adding something early in the application bootstrapping process.
     *
     * @param $app
     * @return void
     */
    protected function getPackageProviders($app)
    {
        return [
            BlogPackageServiceProvider::class,
        ];
    }

    /**
     * Load Service Providers and perform environment setup
     *
     * @param $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // import the migration classes
        include_once __DIR__ . '/../database/migrations/create_users_table.php.stub';
        include_once __DIR__ . '/../database/migrations/create_posts_table.php.stub';

        // run the up() methods of migration classes. Order is important.
        (new \CreateUsersTable)->up();
        (new \CreatePostsTable)->up();
    }
}
