<h1 align="center">
  The chunk upload for laravel
</h1>

<p align="center">
  <a href="https://packagist.org/packages/orh/laravel-chunk-upload">
    <img alt="Packagist PHP Version Support" src="https://img.shields.io/packagist/php-v/orh/laravel-chunk-upload">
  </a>
  <a href="https://packagist.org/packages/orh/laravel-chunk-upload">
    <img alt="Packagist Version" src="https://img.shields.io/packagist/v/orh/laravel-chunk-upload?color=df8057">
  </a>
  <a href="https://packagist.org/packages/orh/laravel-chunk-upload">
    <img alt="Packagist Downloads" src="https://img.shields.io/packagist/dt/orh/laravel-chunk-upload">
  </a>
</p>

* 适用于 Laravel 的分片上传扩展
* PHP 7.0+
* Laravel 5.5+
* 可以配合 [@orh/vue-chunk-upload](https://github.com/ouronghuang/vue-chunk-upload) 使用
* 可以结合相关的 Laravel 云存储扩展一起使用，只需配置磁盘即可

## 使用

1. 安装

```bash
$ composer require orh/laravel-chunk-upload
```

2. 发布配置文件

```bash
$ php artisan vendor:publish --tag=chunk-upload-config
```

3. 使用清除命令，可以加入计划任务，每月定时清除上个月数据

```bash
// 清除上一个月的分片上传数据
$ php artisan chunk-upload:clear
```

## 使用

```php
use Illuminate\Http\Request;

// 预处理
app('chunk-upload')->preprocess($request);

// 上传
app('chunk-upload')->save($request);
```

## 示例

1. 创建控制器

```bash
$ php artisan make:controller ChunkUploadsController
```

2. 调用

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChunkUploadsController extends Controller
{
    public function preprocess(Request $request)
    {
        $this->validate($request, [
            'filename' => 'required|string',
            'size' => 'required|numeric|max:'.config('chunk-upload.allow_size'),
        ]);

        return app('chunk-upload')->preprocess($request);
    }

    public function upload(Request $request)
    {
        $this->validate($request, [
            'filename' => 'required|string',
            'file' => 'required|file',
            'total' => 'required|integer',
            'index' => 'required|integer',
        ]);

        return app('chunk-upload')->save($request);
    }
}
```

3. 定义路由

```php
use Illuminate\Support\Facades\Route;

Route::post('chunk_uploads/preprocess', 'ChunkUploadsController@preprocess');
Route::post('chunk_uploads/upload', 'ChunkUploadsController@upload');
```

## License

MIT


