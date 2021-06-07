<?php

namespace Orh\LaravelChunkUpload;

class Partial
{
    public $header;
    protected $disk;

    public function __construct($filename)
    {
        $this->header = new Header($filename);
        $this->disk = new Disk("{$filename}.part");
    }

    public function create()
    {
        $this->header->create();

        return $this->disk->put('');
    }

    public function append($content)
    {
        return $this->disk->append($content);
    }

    public function delete()
    {
        $this->header->delete();

        return $this->disk->delete();
    }

    public function exists()
    {
        return $this->disk->exists();
    }

    public function rename()
    {
        $filename = $this->disk->getFilename();
        $filename = str_replace('.part', '', $filename);
        $this->disk->move($filename);

        return $filename;
    }
}
