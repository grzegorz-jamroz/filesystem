<?php

declare(strict_types=1);

namespace Ifrost\Filesystem;

use Ifrost\Filesystem\File as Operation;

class JsonFile extends File implements JsonFileInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(array $data = [], int $flags = 0, int $depth = 512): void
    {
        $json = json_encode($data, $flags, $depth) ?: '';

        (new Operation\CreateFileIfNotExists($this->filename, $json))->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function overwrite(array $data, int $flags = 0, int $depth = 512): void
    {
        $json = json_encode($data, $flags, $depth) ?: '';

        (new Operation\OverwriteFile($this->filename, $json))->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function read(bool $associative = true, int $flags = 0, int $depth = 512): array
    {
        $content = (new Operation\ReadFile($this->filename))->acquire();
        $data = json_decode($content, $associative, $depth, $flags);

        return is_array($data) ? $data : [];
    }
}
