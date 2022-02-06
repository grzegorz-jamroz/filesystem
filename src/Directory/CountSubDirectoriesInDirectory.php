<?php

declare(strict_types=1);

namespace Ifrost\Filesystem\Directory;

use Ifrost\Foundations\Acquirable;

class CountSubDirectoriesInDirectory implements Acquirable
{
    /**
     * @param array<string, mixed> $options
     * @GetSubDirectoriesFromDirectory - the same options
     */
    public function __construct(
        private string $directoryPath,
        private array $options = [],
    ) {
    }

    public function acquire(): int
    {
        return count((new GetSubDirectoriesFromDirectory($this->directoryPath, $this->options))->acquire());
    }
}
