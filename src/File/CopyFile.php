<?php

declare(strict_types=1);

namespace Ifrost\Filesystem\File;

use Ifrost\Filesystem\Directory\CreateDirectoryIfNotExists;
use Ifrost\Filesystem\Directory\Exception\DirectoryAlreadyExists;
use Ifrost\Filesystem\File\Exception\FileAlreadyExists;
use Ifrost\Filesystem\File\Exception\FileNotExist;
use Ifrost\Foundations\Executable;

class CopyFile implements Executable
{
    /**
     * @param string $oldFilename fully path to file
     * @param string $newFilename fully path to file
     */
    public function __construct(
        private string $oldFilename,
        private string $newFilename,
    ) {
    }

    /**
     * Makes a copy of the file if it exists.
     * The new file cannot exist.
     * The method will create the missing directories if necessary.
     *
     * @throws FileNotExist      when the old file does not exist
     * @throws FileAlreadyExists when the new file already exists
     */
    public function execute(): void
    {
        if (!is_file($this->oldFilename)) {
            throw new FileNotExist(sprintf('Unable copy file "%s". Old file does not exist.', $this->oldFilename));
        }

        if (is_file($this->newFilename)) {
            throw new FileAlreadyExists(sprintf('Unable copy file "%s". New file already exists.', $this->newFilename));
        }

        $directoryPath = (new GetDirectoryPath($this->newFilename))->acquire();

        try {
            (new CreateDirectoryIfNotExists($directoryPath))->execute();
        } catch (DirectoryAlreadyExists) {
        }

        try {
            copy($this->oldFilename, $this->newFilename) ?: throw new \RuntimeException();
        } catch (\Exception) {
            throw new \RuntimeException(sprintf('Unable copy file "%s". ', $this->oldFilename));
        }
    }
}
