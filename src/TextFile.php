<?php

declare(strict_types=1);

namespace Ifrost\Filesystem;

use Ifrost\Filesystem\File as Operation;

class TextFile extends Operation implements TextFileInterface
{
    public function create(string $content = ''): void
    {
        (new Operation\CreateFileIfNotExists($this->filename, $content))->execute();
    }

    public function write(string $content): void
    {
        (new Operation\WriteFile($this->filename, $content))->execute();
    }

    public function overwrite(string $content): void
    {
        (new Operation\OverwriteFile($this->filename, $content))->execute();
    }

    public function read(): string
    {
        return (new Operation\ReadFile($this->filename))->acquire();
    }
}
