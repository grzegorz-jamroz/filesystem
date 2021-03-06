<?php

declare(strict_types=1);

namespace Tests\Unit\File;

use Ifrost\Filesystem\File;
use Ifrost\Filesystem\File\Exception\FileAlreadyExists;
use Ifrost\Filesystem\File\Exception\FileNotExist;
use Ifrost\Filesystem\TextFile;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class CopyFileTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldCopyFileInsideSameDirectory()
    {
        // Expect & Given
        $oldFilename = sprintf('%s/file/copy-file/something.txt', DATA_DIRECTORY);
        $newFilename = sprintf('%s/file/copy-file/something-copy.txt', DATA_DIRECTORY);
        (new File($oldFilename))->delete();
        $this->createFileIfNotExists($oldFilename, 'this is copy test');
        (new File($newFilename))->delete();
        $this->assertFileExists($oldFilename);
        $this->assertFileDoesNotExist($newFilename);

        // When
        (new File($oldFilename))->copy($newFilename);

        // Then
        $this->assertFileExists($oldFilename);
        $this->assertFileExists($newFilename);
        $this->assertEquals('this is copy test', (new TextFile($newFilename))->read());
    }

    public function testShouldCopyFileAndMoveToNewNotExistedDirectory()
    {
        // Expect & Given
        $oldFilename = sprintf('%s/file/copy-file/dog.txt', DATA_DIRECTORY);
        $newFilename = sprintf('%s/file/copy-file/new_directory/dog-copy.txt', DATA_DIRECTORY);
        (new File($oldFilename))->delete();
        $this->createFileIfNotExists($oldFilename, 'dog has four paths');
        (new File($newFilename))->delete();
        $this->assertFileExists($oldFilename);
        $this->assertFileDoesNotExist($newFilename);

        // When
        (new File($oldFilename))->copy($newFilename);

        // Then
        $this->assertFileExists($oldFilename);
        $this->assertFileExists($newFilename);
        $this->assertEquals('dog has four paths', (new TextFile($newFilename))->read());
    }

    public function testShouldThrowFileNotExistWhenOldFileDoesNotExist()
    {
        // Expect & Given
        $oldFilename = sprintf('%s/file/copy-file/exists_old.txt', DATA_DIRECTORY);
        $newFilename = sprintf('%s/file/copy-file/exists_new.txt', DATA_DIRECTORY);
        $this->expectException(FileNotExist::class);
        $this->expectExceptionMessage(sprintf('Unable copy file "%s". Old file does not exist.', $oldFilename));
        (new File($oldFilename))->delete();
        (new File($newFilename))->delete();
        $this->assertFileDoesNotExist($oldFilename);
        $this->assertFileDoesNotExist($newFilename);

        // When & Then
        (new File($oldFilename))->copy($newFilename);
    }

    public function testShouldThrowFileAlreadyExistsWhenNewFileExists()
    {
        // Expect & Given
        $oldFilename = sprintf('%s/file/copy-file/exists_old.txt', DATA_DIRECTORY);
        $newFilename = sprintf('%s/file/copy-file/exists_new.txt', DATA_DIRECTORY);
        $this->expectException(FileAlreadyExists::class);
        $this->expectExceptionMessage(sprintf('Unable copy file "%s". New file already exists.', $newFilename));
        $this->createFileIfNotExists($oldFilename);
        $this->createFileIfNotExists($newFilename);
        $this->assertFileExists($oldFilename);
        $this->assertFileExists($newFilename);

        // When & Then
        (new File($oldFilename))->copy($newFilename);
    }

    /**
     * it probably only works on ext2/ext3/ext4 filesystems.
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToCopyFile()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['SUDOER_PASSWORD']);

        // Expect & Given
        $oldFilename = sprintf('%s/immutable_file.txt', TESTS_DATA_DIRECTORY);
        $newFilename = sprintf('%s/immutable_file_new.txt', TESTS_DATA_DIRECTORY);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable copy file "%s". ', $oldFilename));
        (new File($newFilename))->delete();
        $this->createProtectedFile($oldFilename);
        $this->createImmutableFile($oldFilename);
        $this->assertFileExists($oldFilename);
        $this->assertFileDoesNotExist($newFilename);

        // When & Then
        (new File($oldFilename))->copy($newFilename);
    }
}
