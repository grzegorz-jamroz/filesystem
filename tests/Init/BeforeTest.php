<?php

declare(strict_types=1);

namespace Tests\Init;

use Ifrost\Filesystem\Directory;
use PHPUnit\Framework\TestCase;

class BeforeTest extends TestCase
{
    public function testShouldCleanUpBeforeAllTests()
    {
        // Given
        $directory = DATA_DIRECTORY;

        // When
        try {
            (new Directory(DATA_DIRECTORY))->delete();
        } catch (\Exception) {
        }

        // Then
        $this->assertDirectoryDoesNotExist($directory);
    }
}
