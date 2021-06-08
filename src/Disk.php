<?php

namespace Orh\LaravelChunkUpload;

use Illuminate\Support\Facades\Storage;

class Disk
{
    protected $file;
    protected $disk;

    public function __construct($file)
    {
        $this->file = config('chunk-upload.path').'/'.date('Y/m').'/'.$file;
        $this->disk = Storage::disk(config('chunk-upload.disk'));
    }

    public function getDisk()
    {
        return $this->disk;
    }

    public function getFilename()
    {
        return $this->file;
    }

    public function put($content)
    {
        return $this->disk->put($this->file, $content);
    }

    public function append($content)
    {
        if ($this->exists()) {
            $content = $this->get().$content;
        }

        return $this->put($content);
    }

    public function get()
    {
        return $this->disk->get($this->file);
    }

    public function exists()
    {
        return $this->disk->exists($this->file);
    }

    public function move($target)
    {
        $this->disk->delete($target);

        return $this->disk->move($this->file, $target);
    }

    public function delete()
    {
        return $this->disk->delete($this->file);
    }
}
