<h1 align="center"> laravel-filesystem-oss </h1>

<p align="center"> .</p>


## Installing

```shell
$ composer require lonelywalkersource/laravel-filesystem-oss
```


# Configuration

1. After installing the library, register the `LonelyWalkerSource\LaravelFilesystemOss\OssStorageServiceProvider` in your `config/app.php` file:

```php
'providers' => [
    LonelyWalkerSource\LaravelFilesystemOss\OssStorageServiceProvider::class,
],
```

2. Add a new disk to your `config/filesystems.php` config:

```php
<?php

return [
   'disks' => [
        //...
        'oss' => [
            'driver' => 'oss',
            'access_key' => env('OSS_ACCESS_KEY_ID'),
            'secret_key' => env('OSS_ACCESS_KEY_SECRET'),
            'bucket' => env('OSS_BUCKET'),
            'endpoint' => env('OSS_ENDPOINT'), // OSS 外网节点或自定义外部域名
            'cnd_domain' => env('OSS_CDN_DOMAIN'), // 如果isCName为true, getUrl会判断cdnDomain是否设定来决定返回的url，如果cdnDomain未设置，则使用endpoint来生成url，否则使用cdn
            'ssl' => false, // true to use 'https://' and false to use 'http://'. default is false,
            'is_cname' => false, // 是否使用自定义域名,true: 则Storage.url()会使用自定义的cdn或域名生成文件url， false: 则使用外部节点生成url
            'debug' => false,
            'prefix' => env('OSS_PREFIX','')
        ],
        //...
    ]
];
```

# Usage

```php
$disk = Storage::disk('oss');

// create a file
$disk->put('avatars/filename.jpg', $fileContents);

// get timestamp
$time = $disk->lastModified('file1.jpg');

// copy a file
$disk->copy('old/file1.jpg', 'new/file1.jpg');

// move a file
$disk->move('old/file1.jpg', 'new/file1.jpg');

// get file contents
$contents = $disk->read('folder/my_file.txt');


// get file url
$url = $disk->url('folder/my_file.txt');
$url = $disk->getAdapter()->getUrl('folder/my_file.txt');

// get private url
$url = $disk->temporaryUrl('folder/my_file.txt');
$url = $disk->getAdapter()->getTemporaryUrl('folder/my_file.txt');
```

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/lonelywalkersource/laravel-filesystem-oss/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/lonelywalkersource/laravel-filesystem-oss/issues).
3. Contribute new features or update the wiki.

_The code contribution process is not very formal. You just need to make sure that you follow the PSR-0, PSR-1, and PSR-2 coding guidelines. Any new code contributions must be accompanied by unit tests where applicable._

## License

MIT