<?php

namespace Detosphere\BlogPackage\Tests;

use Detosphere\BlogPackage\BlogPackageServiceProvider;

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
     * Load Service Providers
     *
     * @param $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
