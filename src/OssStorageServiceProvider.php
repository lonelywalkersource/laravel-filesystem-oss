<?php

namespace Lonelywalksource\LaravelFilesystemOss;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Lonelywalksource\Flysystem\Oss\Config\OssConfig;
use Lonelywalksource\Flysystem\Oss\OssAdapter;

class OssStorageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bound for consumers who resolve OssConfig via container, e.g. app(OssConfig::class).
        // Note: this reads the default 'oss' disk. If you have multiple OSS disks,
        // resolve OssConfig from the boot() callback's $config parameter instead.
        $this->app->singleton(OssConfig::class, function ($app) {
            $config = $app['config']->get('filesystems.disks.oss', []);

            return OssConfig::fromArray($config);
        });
    }

    public function boot(): void
    {
        app('filesystem')->extend('oss', function ($_app, array $config) {
            if (empty($config['access_key']) || empty($config['secret_key']) || empty($config['bucket']) || empty($config['endpoint'])) {
                throw new \RuntimeException('OSS filesystem disk requires access_key, secret_key, bucket, and endpoint.');
            }

            $ossConfig = OssConfig::fromArray($config);
            $adapter = new OssAdapter($ossConfig);

            return new FilesystemAdapter(
                new Filesystem($adapter),
                $adapter,
                $config,
            );
        });
    }
}
