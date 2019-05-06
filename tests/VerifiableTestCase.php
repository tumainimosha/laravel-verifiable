<?php

namespace Tumainimosha\Verifiable\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Tumainimosha\Verifiable\VerifiableServiceProvider;

class VerifiableTestCase extends BaseTestCase
{
    public function setup(): void
    {
        parent::setUp();

        // load test migrations and factories
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->withFactories(__DIR__ . '/database/factories');

        $this->withoutExceptionHandling();
        $this->artisan('migrate', ['--database' => 'testing']);
        // $this->loadLaravelMigrations(['--database' => 'testing']);
        $this->withFactories(__DIR__ . '/../src/database/factories');
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [VerifiableServiceProvider::class];
    }
}
