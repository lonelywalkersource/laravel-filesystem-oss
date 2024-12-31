<?php

namespace LonelyWalkerSource\LaravelFilesystemOss;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use LonelyWalkerSource\FilesystemOss\OssAdapter;
use OSS\OssClient;

class OssStorageServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app('filesystem')->extend('oss', function ($app, $config) {

            $accessId = $config['access_key'];
            $accessKey = $config['secret_key'];

            $cdnDomain = empty($config['cdn_domain']) ? '' : $config['cdn_domain'];
            $bucket = $config['bucket'];
            $ssl = empty($config['ssl']) ? false : $config['ssl'];
            $isCname = empty($config['is_cname']) ? false : $config['is_cname'];
            $debug = empty($config['debug']) ? false : $config['debug'];

            $endPoint = $config['endpoint']; // 默认作为外部节点
            $epInternal = $isCname ? $cdnDomain : (empty($config['endpoint_internal']) ? $endPoint : $config['endpoint_internal']); // 内部节点
            $prefix = isset($config['upload_prefix']) && $config['upload_prefix'] ? $config['upload_prefix'] : '';

            if ($debug) {
                Log::debug('OSS config:', $config);
            }

            $client = new OssClient($accessId, $accessKey, $epInternal, $isCname);
            $adapter = new OssAdapter($client, $bucket, $endPoint, $ssl, $isCname, $debug, $cdnDomain, $prefix);

            return new FilesystemAdapter(new Filesystem($adapter), $adapter, $config);
        });
    }
}
