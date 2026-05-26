# lonelywalkersource/laravel-filesystem-oss

> [中文文档](README-ZH.md)

Alibaba Cloud OSS storage driver for Laravel.

### Requirements

- PHP ^8.2
- Laravel ^11.0 | ^12.0 | ^13.0
- lonelywalkersource/flysystem-oss ^1.0

### Installation

```bash
composer require lonelywalkersource/laravel-filesystem-oss
```

### Configuration

Add an `oss` disk to `config/filesystems.php`:

```php
'disks' => [
    // ...
    'oss' => [
        // ── Required ────────────────────────────────────
        'driver'      => 'oss',

        // Alibaba Cloud AccessKey ID (alias: 'key')
        'access_key'  => env('OSS_ACCESS_KEY'),

        // Alibaba Cloud AccessKey Secret (alias: 'secret')
        'secret_key'  => env('OSS_SECRET_KEY'),

        // OSS endpoint, e.g. oss-cn-hangzhou.aliyuncs.com
        'endpoint'    => env('OSS_ENDPOINT', 'oss-cn-hangzhou.aliyuncs.com'),

        // Bucket name
        'bucket'      => env('OSS_BUCKET'),

        // ── Optional ────────────────────────────────────

        // Region (e.g. cn-hangzhou) — required for V4 signature and post policy
        'region'      => env('OSS_REGION', 'cn-hangzhou'),

        // Whether endpoint is a custom domain (CNAME). Default: false
        'isCName'     => false,

        // STS security token (for temporary credentials)
        'security_token' => env('OSS_SECURITY_TOKEN'),

        // CDN domain — when set, Storage::url() returns CDN URLs
        'domain'      => env('OSS_DOMAIN'),

        // Path prefix for all OSS object keys. Default: ''
        'path_prefix' => '',

        // Multi-bucket configuration — see "Multiple Buckets" section below
        'buckets'     => [],

        // Signature version for OSS client, e.g. OssClient::OSS_SIGNATURE_VERSION_V4
        'signature_version' => null,

        // Connection timeout in seconds
        'timeout'     => null,

        // ── Flysystem Options ───────────────────────────

        // Whether to throw exceptions. Default: true
        'throw'       => true,

        // ── OssClient Extra Options ─────────────────────
        // Any key not listed above is passed directly to the OssClient constructor.
        // Valid OssClient options include: request_proxy, forcePathStyle, cloudBoxId,
        // strictObjectName, checkObjectEncoding, filePathCompatible.
        // Example:
        // 'request_proxy' => 'http://proxy.example.com:8080',
    ],
],
```

Add environment variables to `.env`:

```
OSS_ACCESS_KEY=your-access-key
OSS_SECRET_KEY=your-secret-key
OSS_ENDPOINT=oss-cn-hangzhou.aliyuncs.com
OSS_BUCKET=your-bucket
OSS_REGION=cn-hangzhou
OSS_SECURITY_TOKEN=
OSS_DOMAIN=https://cdn.example.com
```

### Usage

```php
// Store a file
Storage::disk('oss')->put('path/to/file.txt', 'Hello OSS');

// Get the URL
$url = Storage::disk('oss')->url('path/to/file.txt');

// Temporary URL
$tempUrl = Storage::disk('oss')->temporaryUrl('path/to/file.txt', now()->addHour());

// Direct access to adapter
$adapter = Storage::disk('oss')->getAdapter();
$postPolicy = $adapter->generatePostPolicy([
    'expire' => 1800,
    'prefix' => 'uploads/',
]);
```

### Multiple Buckets

Add additional disks in `config/filesystems.php`:

```php
'oss' => [
    'driver'      => 'oss',
    'access_key'  => env('OSS_ACCESS_KEY'),
    'secret_key'  => env('OSS_SECRET_KEY'),
    'endpoint'    => env('OSS_ENDPOINT'),
    'bucket'      => env('OSS_BUCKET'),
    'region'      => env('OSS_REGION'),
    'buckets'     => [
        'images' => [
            'bucket'   => env('OSS_BUCKET_IMAGES'),
            'endpoint' => env('OSS_ENDPOINT_IMAGES'),
        ],
    ],
],
```

```php
// Access secondary bucket via the adapter
$adapter = Storage::disk('oss')->getAdapter();
$imagesAdapter = $adapter->bucket('images');
```

### License

MIT
