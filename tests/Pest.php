<?php

use Dotenv\Dotenv;
use Lonelywalksource\LaravelFilesystemOss\OssStorageServiceProvider;
use Orchestra\Testbench\TestCase;

Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();

class OssPackage extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [OssStorageServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('filesystems.disks.oss', [
            'driver'     => 'oss',
            'access_key' => env('OSS_ACCESS_KEY_ID', ''),
            'secret_key' => env('OSS_ACCESS_KEY_SECRET', ''),
            'endpoint'   => env('OSS_ENDPOINT', ''),
            'bucket'     => env('OSS_BUCKET', ''),
            'isCName'    => filter_var(env('OSS_CNAME', false), FILTER_VALIDATE_BOOLEAN),
            'region'     => env('OSS_REGION'),
            'prefix'     => env('OSS_PREFIX', ''),
        ]);
    }
}

uses(OssPackage::class)->in(__DIR__);
