# lonelywalkersource/laravel-filesystem-oss

> [English Documentation](README.md)

阿里云 OSS 对象存储的 Laravel 驱动。

### 环境要求

- PHP ^8.2
- Laravel ^11.0 | ^12.0 | ^13.0
- lonelywalkersource/flysystem-oss ^1.0

### 安装

```bash
composer require lonelywalkersource/laravel-filesystem-oss
```

### 配置

在 `config/filesystems.php` 中添加 `oss` disk：

```php
'disks' => [
    // ...
    'oss' => [
        // ── 必填项 ──────────────────────────────────────
        'driver'      => 'oss',

        // 阿里云 AccessKey ID（别名：key）
        'access_key'  => env('OSS_ACCESS_KEY'),

        // 阿里云 AccessKey Secret（别名：secret）
        'secret_key'  => env('OSS_SECRET_KEY'),

        // OSS Endpoint，如 oss-cn-hangzhou.aliyuncs.com
        'endpoint'    => env('OSS_ENDPOINT', 'oss-cn-hangzhou.aliyuncs.com'),

        // Bucket 名称
        'bucket'      => env('OSS_BUCKET'),

        // 地域（如 cn-hangzhou）—— V4 签名和 Post Policy 需要
        'region'      => env('OSS_REGION', 'cn-hangzhou'),

        // 是否为自定义域名（CNAME）。默认：false
        'isCName'     => env('OSS_CNAME', false),

        // CDN 域名 —— 设置后 Storage::url() 返回 CDN 地址
        'domain'      => env('OSS_DOMAIN',''),

        // OSS 对象 Key 的路径前缀。默认：''
        'path_prefix' => env('OSS_PATH_PREFIX', ''),

        // 多 Bucket 配置 —— 见下方「多 Bucket 支持」章节
        'buckets'     => [],

        // OSS 客户端签名版本，如 OssClient::OSS_SIGNATURE_VERSION_V4
        'signature_version' => OssClient::OSS_SIGNATURE_VERSION_V4,

        // 连接超时时间（秒）
        'timeout'     => 30,

        // ── Flysystem 选项 ──────────────────────────────

        // 是否抛出异常。默认：true
        'throw'       => false,

        // ── OssClient 额外配置 ──────────────────────────
        // 以上列表之外的 key 会直接传递给 OssClient 构造函数。
        // 有效的 OssClient 选项包括：request_proxy、forcePathStyle、cloudBoxId、
        // strictObjectName、checkObjectEncoding、filePathCompatible。
        // 示例：
        // 'request_proxy' => 'http://proxy.example.com:8080',
    ],
],
```

在 `.env` 中添加环境变量：

```
OSS_ACCESS_KEY=your-access-key
OSS_SECRET_KEY=your-secret-key
OSS_ENDPOINT=oss-cn-hangzhou.aliyuncs.com
OSS_BUCKET=your-bucket
OSS_REGION=cn-hangzhou
OSS_SECURITY_TOKEN=
OSS_DOMAIN=https://cdn.example.com
```

### 使用

```php
// 存储文件
Storage::disk('oss')->put('path/to/file.txt', 'Hello OSS');

// 获取 URL
$url = Storage::disk('oss')->url('path/to/file.txt');

// 临时 URL
$tempUrl = Storage::disk('oss')->temporaryUrl('path/to/file.txt', now()->addHour());

// 直接访问适配器
$adapter = Storage::disk('oss')->getAdapter();
$postPolicy = $adapter->generatePostPolicy([
    'expire' => 1800,
    'prefix' => 'uploads/',
]);
```

### 多 Bucket 支持

在 `config/filesystems.php` 中配置：

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
// 通过适配器访问其他 bucket
$adapter = Storage::disk('oss')->getAdapter();
$imagesAdapter = $adapter->bucket('images');
```

### License

MIT
