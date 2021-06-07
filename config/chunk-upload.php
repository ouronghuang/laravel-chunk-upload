<?php

return [
    /*
    |--------------------------------------------------------------------------
    | 默认上传磁盘
    |--------------------------------------------------------------------------
    |
    */
    'disk' => env('CHUNK_UPLOAD_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | 默认上传路径
    |--------------------------------------------------------------------------
    |
    */
    'path' => env('CHUNK_UPLOAD_PATH', 'chunk-upload/'.date('Y/m')),

    /*
    |--------------------------------------------------------------------------
    | 默认分片大小
    |--------------------------------------------------------------------------
    |
    | 1M => 1 * 1024 * 1024 => 1048576 [default]
    | 2M => 2 * 1024 * 1024 => 2097152
    | 3M => 3 * 1024 * 1024 => 3145728
    | 4M => 4 * 1024 * 1024 => 4194304
    | 5M => 5 * 1024 * 1024 => 5242880
    |
    */
    'size' => env('CHUNK_UPLOAD_SIZE', 1048576),

    /*
    |--------------------------------------------------------------------------
    | 默认允许上传的文件类型
    |--------------------------------------------------------------------------
    |
    */
    'extensions' => [],

    /*
    |--------------------------------------------------------------------------
    | 默认上传表单名称
    |--------------------------------------------------------------------------
    |
    */
    'request' => [
        'filename' => 'filename',
        'total' => 'total',
        'index' => 'index',
        'file' => 'file',
    ],

    /*
    |--------------------------------------------------------------------------
    | 默认响应字段名称
    |--------------------------------------------------------------------------
    |
    */
    'response' => [
        'filename' => 'filename',
        'size' => 'size',
        'path' => 'path',
    ],
];
