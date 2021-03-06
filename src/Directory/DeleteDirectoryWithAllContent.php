<?php

declare(strict_types=1);

namespace Ifrost\Filesystem\Directory;

use Ifrost\Foundations\Executable;
use Ifrost\Filesystem\File\DeleteFile;

class DeleteDirectoryWithAllContent implements Executable
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function execute(): void
    {
        $this->delete($this->path);
    }

    private function delete(string $dirPath): void
    {
        if (!file_exists($dirPath)) {
            return;
        }

        if (!is_dir($dirPath)) {
            throw new \InvalidArgumentException(sprintf('%s is not directory.', $dirPath));
        }

        if (substr($dirPath, strlen($dirPath) - 1, 1) !== '/') {
            $dirPath .= '/';
        }

        $files = glob($dirPath . '*', GLOB_MARK) ?: [];

        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->delete($file);

                continue;
            }

            (new DeleteFile($file))->execute();
        }

        try {
            rmdir($dirPath) ?: throw new \RuntimeException();
        } catch (\Exception) {
            $dirPath = strlen($dirPath) > 1 ? rtrim($dirPath, '/') : $dirPath;

            throw new \RuntimeException(sprintf('Unable remove directory "%s".', $dirPath));
        }
    }
}
