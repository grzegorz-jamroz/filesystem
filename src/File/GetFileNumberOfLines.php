<?php

declare(strict_types=1);

namespace Ifrost\Filesystem\File;

use Exception;
use Ifrost\Foundations\Acquirable;
use RuntimeException;

class GetFileNumberOfLines implements Acquirable
{
    /**
     * @var string fully path to file
     */
    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function acquire(): int
    {
        if (!is_file($this->filename)) {
            throw new RuntimeException(sprintf('Unable to read file. File %s not exist.', $this->filename));
        }

        $count = 0;

        try {
            $file = fopen($this->filename, 'r') ?: throw new RuntimeException();
        } catch (Exception) {
            throw new RuntimeException(sprintf('Unable to read file %s.', $this->filename));
        }

        while (!feof($file)) {
            fgets($file);
            ++$count;
        }

        fclose($file);

        return $count;
    }
}
