<?php

declare(strict_types=1);

namespace Tests\Unit\File;

use Ifrost\Filesystem\File;
use Ifrost\Filesystem\File\Exception\FileNotExist;
use Ifrost\Filesystem\TextFile;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class ReadFileTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldReturnEmptyStringWhenFileIsEmpty()
    {
        // Expect & Given
        $filename = sprintf('%s/file/read-file/empty.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->createFileIfNotExists($filename);
        $this->assertFileExists($filename);

        // When
        $content = (new TextFile($filename))->read();

        //Then
        $this->assertEquals('', $content);
    }

    public function testShouldReturnStringAsContent()
    {
        // Expect & Given
        $filename = sprintf('%s/file/read-file/empty.txt', DATA_DIRECTORY);
        (new File($filename))->delete();
        $this->createFileIfNotExists($filename, 'hello world');
        $this->assertFileExists($filename);

        // When
        $content = (new TextFile($filename))->read();

        //Then
        $this->assertEquals('hello world', $content);
    }

    public function testShouldThrowRuntimeExceptionWhenFileDoesNotExist()
    {
        // Expect & Given
        $filename = sprintf('%s/file/read-file/sample_%s.txt', DATA_DIRECTORY, time());
        $this->expectException(FileNotExist::class);
        $this->expectExceptionMessage(sprintf('Unable to read file. File %s not exist.', $filename));
        $this->assertFileDoesNotExist($filename);

        // When & Then
        (new TextFile($filename))->read();
    }

    /**
     * it probably only works on ext2/ext3/ext4 filesystems.
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToReadFile()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['SUDOER_PASSWORD']);

        // Expect & Given
        $filename = sprintf('%s/protected.txt', TESTS_DATA_DIRECTORY);
        $this->createProtectedFile($filename);
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable to read content of file %s.', $filename));
        $this->assertFileExists($filename);

        // When & Then
        (new TextFile($filename))->read();
    }
}
