<?php

namespace Orh\LaravelChunkUpload;

use Illuminate\Http\Request;
use Orh\LaravelChunkUpload\Exceptions\InvalidArgumentException;
use Orh\LaravelChunkUpload\Exceptions\InvalidExtensionException;
use Orh\LaravelChunkUpload\Exceptions\InvalidIndexException;
use Orh\LaravelChunkUpload\Exceptions\NotExistsException;

class ChunkUpload
{
    public function preprocess(Request $request): array
    {
        $filename = $request->input(config('chunk-upload.request.filename'));

        if (! $filename) {
            throw new InvalidArgumentException('The filename is required.');
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (! $extension) {
            throw new InvalidArgumentException('The extension is invalid.');
        }

        $extensions = config('chunk-upload.extensions');

        if (count($extensions) && ! in_array($extension, $extensions)) {
            throw new InvalidExtensionException("The target chunk file extension [{$extension}] is not allowed.");
        }

        $filename = time().mt_rand(100000, 999999).'.'.$extension;

        $partial = new Partial($filename);
        $partial->create();

        return [
            config('chunk-upload.response.filename') => $filename,
            config('chunk-upload.response.size') => config('chunk-upload.size'),
        ];
    }

    public function save(Request $request): array
    {
        $filename = $request->input(config('chunk-upload.request.filename'));
        $total = (int)$request->input(config('chunk-upload.request.total'));
        $index = (int)$request->input(config('chunk-upload.request.index'));
        $file = $request->file(config('chunk-upload.request.file'));

        $partial = new Partial($filename);

        if (! $partial->exists()) {
            throw new NotExistsException("The target chunk file [{$filename}] is not exists.");
        }

        if ((int)($partial->header->read()) !== $index - 1) {
            throw new InvalidIndexException("The target chunk file [{$filename}] index is not invalid.");
        }

        $partial->append($file->getContent());
        $partial->header->write($index);

        $path = '';

        if ($total === $index) {
            $partial->header->delete();
            $path = $partial->rename();
        }

        return [
            config('chunk-upload.response.path') => $path,
        ];
    }
}
