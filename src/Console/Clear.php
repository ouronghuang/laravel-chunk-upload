<?php

namespace Orh\LaravelChunkUpload\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class Clear extends Command
{
    protected $signature = 'chunk-upload:clear';

    protected $description = '清除上一个月的分片上传数据';

    public function handle()
    {
        $disk = Storage::disk(config('chunk-upload.disk'));
        $path = config('chunk-upload.path').'/'.now()->subMonth()->format('Y/m');
        $disk->deleteDirectory($path);

        return 0;
    }
}
