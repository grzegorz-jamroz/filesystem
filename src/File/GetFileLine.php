<?php

declare(strict_types=1);

namespace Ifrost\Filesystem\File;

use Ifrost\Foundations\Acquirable;

class GetFileLine implements Acquirable
{
    public function __construct(
        private string $filename,
        private int $lineNumber,
    ) {
    }

    public function acquire(): string
    {
        if (!is_file($this->filename)) {
            throw new \RuntimeException(sprintf('Unable to read file. File %s not exist.', $this->filename));
        }

        $count = 0;
        $this->lineNumber > 0 ?: throw new \InvalidArgumentException('Line number has to be greater than 0".');

        try {
            $file = fopen($this->filename, 'r') ?: throw new \RuntimeException();
        } catch (\Exception) {
            throw new \RuntimeException(sprintf('Unable to read file %s.', $this->filename));
        }

        while (!feof($file)) {
            $line = fgets($file) ?: '';
            ++$count;

            if ($count === $this->lineNumber) {
                return $line;
            }
        }

        fclose($file);

        throw new \Exception(sprintf('Required line %s does not exist inside file %s.', $this->lineNumber, $this->filename));
    }
}
