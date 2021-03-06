<?php

declare(strict_types=1);

namespace Tests\Unit\Directory;

use Ifrost\Filesystem\Directory;
use Ifrost\Filesystem\Directory\Exception\DirectoryAlreadyExists;
use Ifrost\Filesystem\Directory\Exception\DirectoryNotExist;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class RenameDirectoryTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldRenameDirectoryInsideSameDirectory()
    {
        // Expect & Given
        $oldDirectory = sprintf('%s/directory/rename-directory/exists_old', DATA_DIRECTORY);
        $newDirectory = sprintf('%s/directory/rename-directory/exists_new', DATA_DIRECTORY);
        $this->createDirectoryIfNotExists($oldDirectory);
        (new Directory($newDirectory))->delete();
        $this->assertDirectoryExists($oldDirectory);
        $this->assertDirectoryDoesNotExist($newDirectory);

        // When
        (new Directory($oldDirectory))->rename($newDirectory);

        // Then
        $this->assertDirectoryDoesNotExist($oldDirectory);
        $this->assertDirectoryExists($newDirectory);
    }

    public function testShouldRenameDirectoryAndMoveToNewNotExistedDirectory()
    {
        // Expect & Given
        $oldDirectory = sprintf('%s/directory/rename-directory/exists_old', DATA_DIRECTORY);
        $newDirectoryLocation = sprintf('%s/directory/rename-directory/new_directory', DATA_DIRECTORY);
        $newDirectory = sprintf('%s/exists_new', $newDirectoryLocation);
        $this->createDirectoryIfNotExists($oldDirectory);
        (new Directory($newDirectoryLocation))->delete();
        $this->assertDirectoryExists($oldDirectory);
        $this->assertDirectoryDoesNotExist($newDirectory);

        // When
        (new Directory($oldDirectory))->rename($newDirectory);

        // Then
        $this->assertDirectoryDoesNotExist($oldDirectory);
        $this->assertDirectoryExists($newDirectory);
    }

    public function testShouldRenameDirectoryWhichContainsFilesInsideAndMoveToNewNotExistedDirectory()
    {
        // Expect & Given
        $oldDirectory = sprintf('%s/directory/rename-directory/exists_old', DATA_DIRECTORY);
        $filenames = [
            sprintf('%s/directory/rename-directory/exists_old/test1.txt', DATA_DIRECTORY),
            sprintf('%s/directory/rename-directory/exists_old/test2.txt', DATA_DIRECTORY),
            sprintf('%s/directory/rename-directory/exists_old/nested/test3.txt', DATA_DIRECTORY),
            sprintf('%s/directory/rename-directory/exists_old/nested2/test4.txt', DATA_DIRECTORY),
        ];
        $newDirectoryLocation = sprintf('%s/directory/rename-directory/new_directory', DATA_DIRECTORY);
        $newDirectory = sprintf('%s/exists_new', $newDirectoryLocation);
        $this->createDirectoryIfNotExists($oldDirectory);

        foreach ($filenames as $filename) {
            $this->createFileIfNotExists($filename);
            $this->assertFileExists($oldDirectory);
        }

        (new Directory($newDirectoryLocation))->delete();
        $this->assertDirectoryExists($oldDirectory);
        $this->assertDirectoryDoesNotExist($newDirectory);

        // When
        (new Directory($oldDirectory))->rename($newDirectory);

        // Then
        $this->assertDirectoryDoesNotExist($oldDirectory);
        $this->assertDirectoryExists($newDirectory);
        $this->assertEquals(2, (new Directory($newDirectory))->countFiles());
        $this->assertEquals(4, (new Directory($newDirectory))->countFiles(['recursive' => true]));
    }

    public function testShouldThrowRuntimeExceptionWhenOldDirectoryDoesNotExist()
    {
        // Expect & Given
        $oldDirectory = sprintf('%s/directory/rename-directory/exists_old', DATA_DIRECTORY);
        $newDirectory = sprintf('%s/directory/rename-directory/exists_new', DATA_DIRECTORY);
        $this->expectException(DirectoryNotExist::class);
        $this->expectExceptionMessage(sprintf('Unable rename directory "%s". Old directory does not exist.', $oldDirectory));
        (new Directory($oldDirectory))->delete();
        (new Directory($newDirectory))->delete();
        $this->assertDirectoryDoesNotExist($oldDirectory);
        $this->assertDirectoryDoesNotExist($newDirectory);

        // When & Then
        (new Directory($oldDirectory))->rename($newDirectory);
    }

    public function testShouldThrowRuntimeExceptionWhenNewDirectoryExists()
    {
        // Expect & Given
        $oldDirectory = sprintf('%s/directory/rename-directory/exists_old', DATA_DIRECTORY);
        $newDirectory = sprintf('%s/directory/rename-directory/exists_new', DATA_DIRECTORY);
        $this->expectException(DirectoryAlreadyExists::class);
        $this->expectExceptionMessage(sprintf('Unable rename directory "%s". New directory already exists.', $newDirectory));
        $this->createDirectoryIfNotExists($oldDirectory);
        $this->createDirectoryIfNotExists($newDirectory);
        $this->assertDirectoryExists($oldDirectory);
        $this->assertDirectoryExists($newDirectory);

        // When & Then
        (new Directory($oldDirectory))->rename($newDirectory);
    }

    /**
     * it probably only works on ext2/ext3/ext4 filesystems.
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToDeleteDirectory()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['SUDOER_PASSWORD']);

        // Expect & Given
        $oldDirectory = sprintf('%s/immutable_file', TESTS_DATA_DIRECTORY);
        $newDirectory = sprintf('%s/immutable_file_new', TESTS_DATA_DIRECTORY);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable rename directory "%s". ', $oldDirectory));
        $this->createImmutableDirectory($oldDirectory);
        $this->assertDirectoryExists($oldDirectory);
        $this->assertDirectoryDoesNotExist($newDirectory);

        // When & Then
        (new Directory($oldDirectory))->rename($newDirectory);
    }
}
