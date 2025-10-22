<?php

declare(strict_types=1);

namespace Ifrost\Filesystem\Tests\Unit;

use Ifrost\Filesystem\Directory;
use PHPUnit\Framework\TestCase;

class DirectoryTest extends TestCase
{
    public function testShouldReturnDirectoryPath()
    {
        // Given
        $directoryPaths = [
            '/',
            '/a',
            '/data/test/test2',
            '\var\www/data/test/test2',
        ];

        foreach ($directoryPaths as $directoryPath) {
            $this->assertEquals($directoryPath, (new Directory($directoryPath))->getPath());
        }
    }
}
