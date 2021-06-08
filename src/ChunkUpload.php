<?php

namespace Orh\LaravelChunkUpload;

use Illuminate\Http\Request;
use Orh\LaravelChunkUpload\Exceptions\InvalidArgumentException;
use Orh\LaravelChunkUpload\Exceptions\InvalidExtensionException;
use Orh\LaravelChunkUpload\Exceptions\InvalidIndexException;
use Orh\LaravelChunkUpload\Exceptions\InvalidSizeException;
use Orh\LaravelChunkUpload\Exceptions\NotExistsException;

class ChunkUpload
{
    public function preprocess(Request $request): array
    {
        $filename = $request->input(config('chunk-upload.request.filename'));

        if (! $filename) {
            throw new InvalidArgumentException('The [filename] is required.');
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (! $extension) {
            throw new InvalidArgumentException('The [extension] is invalid.');
        }

        $allowExtensions = config('chunk-upload.allow_extensions');

        if (count($allowExtensions) && ! in_array($extension, $allowExtensions)) {
            throw new InvalidExtensionException("The extension [{$extension}] is not allowed.");
        }

        $allowSize = config('chunk-upload.allow_size');
        $size = $request->input(config('chunk-upload.request.size'));

        if ($allowSize && $size > $allowSize) {
            throw new InvalidSizeException("The size [{$size}] must not be greater than {$allowSize}.");
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

        if (! $filename) {
            throw new InvalidArgumentException('The [filename] is required.');
        }

        $total = (int)$request->input(config('chunk-upload.request.total'));

        if (! is_numeric($total)) {
            throw new InvalidArgumentException('The [total] is required.');
        }

        $index = (int)$request->input(config('chunk-upload.request.index'));

        if (! is_numeric($index)) {
            throw new InvalidArgumentException('The [index] is required.');
        }

        $file = $request->file(config('chunk-upload.request.file'));

        if (! $file->isValid()) {
            throw new InvalidArgumentException('The [file] is invalid.');
        }

        $partial = new Partial($filename);

        if (! $partial->exists()) {
            throw new NotExistsException("The file [{$filename}] is not exists.");
        }

        if ((int)($partial->header->read()) !== $index - 1) {
            throw new InvalidIndexException("The file [{$filename}] index is not invalid.");
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
