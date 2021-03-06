<?php

declare(strict_types=1);

namespace Tests\Unit\Directory;

use Ifrost\Filesystem\Directory;
use Ifrost\Filesystem\Directory\Exception\DirectoryAlreadyExists;
use PHPUnit\Framework\TestCase;
use Tests\Traits\TestUtils;

class CreateDirectoryIfNotExistsTest extends TestCase
{
    use TestUtils;

    protected function setUp(): void
    {
        $this->createDirectoryIfNotExists(DATA_DIRECTORY);
    }

    public function testShouldCreateDirectoryInNotExistedDirectory()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/create-directory/sample_%s/test', DATA_DIRECTORY, time());

        // When
        (new Directory($directoryPath))->create();

        // Then
        $this->assertDirectoryExists($directoryPath);
    }

    public function testShouldThrowDirectoryAlreadyExistsWhenDirectoryExists()
    {
        // Expect & Given
        $directoryPath = sprintf('%s/directory/create-directory/already_exists', DATA_DIRECTORY);
        (new Directory($directoryPath))->delete();
        mkdir($directoryPath, 0777, true);
        $this->expectException(DirectoryAlreadyExists::class);
        $this->expectExceptionMessage(sprintf('Unable to create directory "%s". Directory already exists.', $directoryPath));
        $this->assertDirectoryExists($directoryPath);

        // When & Then
        (new Directory($directoryPath))->create();
    }

    /**
     * it probably only works on ext2/ext3/ext4 filesystems.
     */
    public function testShouldThrowRuntimeExceptionWhenUnableToCreateDirectory()
    {
        $this->endTestIfWindowsOs($this);
        $this->endTestIfEnvMissing($this, ['SUDOER_PASSWORD']);

        // Expect & Given
        $directoryPath = sprintf('%s/immutable_dir', TESTS_DATA_DIRECTORY);
        $directoryPath2 = sprintf('%s/sample_%s', $directoryPath, time());
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Unable to create directory "%s".', $directoryPath2));
        $this->createImmutableDirectory($directoryPath);
        $this->assertDirectoryExists($directoryPath);
        $this->assertDirectoryDoesNotExist($directoryPath2);

        // When & Then
        (new Directory($directoryPath2))->create();
    }
}
