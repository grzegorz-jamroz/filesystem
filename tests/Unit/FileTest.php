<?php

declare(strict_types=1);

namespace Tests\Unit;

use Ifrost\Filesystem\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function testShouldReturnFilename()
    {
        // Given
        $filenames = [
            '/one.txt',
            '/a',
            '/data/test/three.txt',
            '\var\www/data/test/four.txt',
        ];

        foreach ($filenames as $filename) {
            $this->assertEquals($filename, (new File($filename))->getFilename());
        }
    }
}
