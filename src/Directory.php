<?php

declare(strict_types=1);

namespace Ifrost\Filesystem;

use Ifrost\Filesystem\Directory as Operation;

class Directory implements DirectoryInterface
{
    /**
     * @param string $path fully path to directory
     */
    public function __construct(private string $path)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function create(
        int $permissions = 0777,
        bool $recursive = true
    ): void {
        (new Operation\CreateDirectoryIfNotExists($this->path, $permissions, $recursive))->execute();
    }

    public function delete(): void
    {
        (new Operation\DeleteDirectoryWithAllContent($this->path))->execute();
    }

    public function copy(string $newDirectoryPath): void
    {
        (new Operation\CopyDirectory($this->path, $newDirectoryPath))->execute();
    }

    public function rename(string $newDirectoryPath): void
    {
        (new Operation\RenameDirectory($this->path, $newDirectoryPath))->execute();
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getParentPath(): string
    {
        return (new Operation\GetDirectoryParentPath($this->path))->acquire();
    }

    /**
     * {@inheritDoc}
     */
    public function getFiles(array $options = []): array
    {
        return (new Operation\GetFilesFromDirectory($this->path, $options))->acquire();
    }

    /**
     * {@inheritDoc}
     */
    public function getDirectories(array $options = []): array
    {
        return (new Operation\GetSubDirectoriesFromDirectory($this->path, $options))->acquire();
    }

    /**
     * {@inheritDoc}
     */
    public function getFilesAndDirectories(array $options = []): array
    {
        return (new Operation\GetFilesAndSubDirectoriesFromDirectory($this->path, $options))->acquire();
    }

    /**
     * {@inheritDoc}
     */
    public function countFiles(array $options = []): int
    {
        return (new Operation\CountFilesInDirectory($this->path, $options))->acquire();
    }

    /**
     * {@inheritDoc}
     */
    public function countDirectories(array $options = []): int
    {
        return (new Operation\CountSubDirectoriesInDirectory($this->path, $options))->acquire();
    }

    /**
     * {@inheritDoc}
     */
    public function countFilesAndDirectories(array $options = []): int
    {
        return (new Operation\CountFilesAndDirectories($this->path, $options))->acquire();
    }
}
