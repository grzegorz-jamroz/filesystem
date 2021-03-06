<?php

declare(strict_types=1);

namespace Tests\Traits;

use Ifrost\Filesystem\Directory;
use Ifrost\Filesystem\Directory\Exception\DirectoryAlreadyExists;
use Ifrost\Filesystem\File\Exception\FileAlreadyExists;
use Ifrost\Filesystem\TextFile;
use PHPUnit\Framework\TestCase;
use PlainDataTransformer\Transform;

trait TestUtils
{
    protected function endTestIfWindowsOs(TestCase $testCase): void
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $testCase->expectException(\RuntimeException::class);
            throw new \RuntimeException('The operating system PHP was built for Windows.');
        }
    }

    /**
     * @param array|string[] $envs
     */
    protected function endTestIfEnvMissing(
        TestCase $testCase,
        array $envs
    ): void {
        foreach ($envs as $env) {
            if (Transform::toString($_ENV[$env] ?? '') === '') {
                $testCase->expectException(\RuntimeException::class);
                throw new \RuntimeException(sprintf('Env %s is missing.', $env));
            }
        }
    }

    protected function createImmutableFile(string $filename): void
    {
        $this->createProtectedFile($filename);
        exec(sprintf('echo %s | sudo -S chattr +i %s > /dev/null 2>&1', $_ENV['SUDOER_PASSWORD'], $filename));
    }

    protected function createImmutableDirectory(string $filename): void
    {
        exec(sprintf('mkdir %s > /dev/null 2>&1', $filename));
        exec(sprintf('echo %s | sudo -S chattr +i %s > /dev/null 2>&1', $_ENV['SUDOER_PASSWORD'], $filename));
    }

    protected function createProtectedFile(string $filename): void
    {
        exec(sprintf('touch %s > /dev/null 2>&1', $filename));
        exec(sprintf('echo %s | sudo -S chmod 000 %s > /dev/null 2>&1', $_ENV['SUDOER_PASSWORD'], $filename));
    }

    protected function createReadOnlyFile(string $filename): void
    {
        exec(sprintf('touch %s > /dev/null 2>&1', $filename));
        exec(sprintf('echo %s | sudo -S chmod 444 %s > /dev/null 2>&1', $_ENV['SUDOER_PASSWORD'], $filename));
    }

    protected function createFileIfNotExists(string $filename, string $content = ''): void
    {
        try {
            (new TextFile($filename))->create($content);
        } catch (FileAlreadyExists) {
        }
    }

    protected function createDirectoryIfNotExists(
        string $directoryPath,
        int $permissions = 0777,
        bool $recursive = true
    ): void {
        try {
            (new Directory($directoryPath))->create($permissions, $recursive);
        } catch (DirectoryAlreadyExists) {
        }
    }
}
