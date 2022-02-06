<?php

declare(strict_types=1);

namespace Ifrost\Filesystem;

use Ifrost\Filesystem\File as Operation;

class File implements FileInterface
{
    /**
     * @param string $filename fully path to file
     */
    public function __construct(protected string $filename)
    {
    }

    public function delete(): void
    {
        (new Operation\DeleteFile($this->filename))->execute();
    }

    public function copy(string $newFilename): void
    {
        (new Operation\CopyFile($this->filename, $newFilename))->execute();
    }

    /**
     * {@inheritDoc}
     */
    public function rename(string $newFilename): void
    {
        (new Operation\RenameFile($this->filename, $newFilename))->execute();
        $this->filename = $newFilename;
    }

    public function getDirectoryPath(): string
    {
        return (new Operation\GetDirectoryPath($this->filename))->acquire();
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getFullName(): string
    {
        return (new Operation\GetFileFullName($this->filename))->acquire();
    }

    public function getName(): string
    {
        return (new Operation\GetFileName($this->filename))->acquire();
    }

    public function getExtension(): string
    {
        return (new Operation\GetFileExtension($this->filename))->acquire();
    }

    public function countLines(): int
    {
        return (new Operation\GetFileNumberOfLines($this->filename))->acquire();
    }

    public function getLine(int $lineNumber): string
    {
        return (new Operation\GetFileLine($this->filename, $lineNumber))->acquire();
    }
}
