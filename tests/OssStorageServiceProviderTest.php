<?php

use Illuminate\Filesystem\FilesystemAdapter;
use Lonelywalksource\Flysystem\Oss\Config\OssConfig;
use Lonelywalksource\Flysystem\Oss\OssAdapter;

test('oss driver is registered', function () {
    $disk = app('filesystem')->disk('oss');

    expect($disk)->toBeInstanceOf(FilesystemAdapter::class);
});

test('oss driver has correct adapter', function () {
    $disk = app('filesystem')->disk('oss');

    expect($disk)->toBeInstanceOf(FilesystemAdapter::class)
        ->and($disk->getAdapter())->toBeInstanceOf(OssAdapter::class);
});

test('oss config is bound to container', function () {
    $config = app(OssConfig::class);

    expect($config)->toBeInstanceOf(OssConfig::class)
        ->and($config->accessKeyId)->toBe(env('OSS_ACCESS_KEY_ID'))
        ->and($config->accessKeySecret)->toBe(env('OSS_ACCESS_KEY_SECRET'))
        ->and($config->bucket)->toBe(env('OSS_BUCKET'));
});
