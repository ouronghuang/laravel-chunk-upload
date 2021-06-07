<?php

namespace Orh\LaravelChunkUpload;

class Header
{
    protected $disk;

    public function __construct($filename)
    {
        $this->disk = new Disk("{$filename}.head");
    }

    public function create()
    {
        return $this->write(0);
    }

    public function write($content)
    {
        return $this->disk->put($content);
    }

    public function read()
    {
        return $this->disk->get();
    }

    public function delete()
    {
        return $this->disk->delete();
    }
}
