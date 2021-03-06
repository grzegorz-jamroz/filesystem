<?php

declare(strict_types=1);

namespace Ifrost\Filesystem;

use Ifrost\Filesystem\File\Exception\FileAlreadyExists;
use Ifrost\Filesystem\File\Exception\FileNotExist;

interface JsonFileInterface extends FileInterface
{
    /**
     * Creates a new file if it does not exist.
     * The method will create the missing directories if necessary.
     *
     * @param array<string, mixed> $data
     * @param int<1, max>          $depth
     *
     * @throws FileAlreadyExists when file already exists
     */
    public function create(array $data = [], int $flags = 0, int $depth = 512): void;

    /**
     * The method will replace all file content with new content.
     * The method will create a new file if it does not exist with given content.
     * The method will create the missing directories if necessary.
     *
     * @param array<string, mixed> $data
     * @param int<1, max>          $depth
     */
    public function overwrite(array $data, int $flags = 0, int $depth = 512): void;

    /**
     * The method will return the contents of the file if it exists.
     *
     * @phpstan-param int<1, max> $depth
     *
     * @return array<string, mixed>
     *
     * @throws FileNotExist
     */
    public function read(bool $associative = true, int $flags = 0, int $depth = 512): array;
}
