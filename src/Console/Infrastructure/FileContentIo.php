<?php

declare(strict_types=1);

namespace Gacela\Console\Infrastructure;

use Gacela\Console\Domain\FileContent\FileContentIoInterface;
use RuntimeException;

/**
 * @codeCoverageIgnore
 */
final class FileContentIo implements FileContentIoInterface
{
    public function mkdir(string $directory): void
    {
        if (is_dir($directory)) {
            return;
        }
        if (!mkdir($directory) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }
    }

    public function filePutContents(string $path, string $fileContent): void
    {
        file_put_contents($path, $fileContent);
    }
}
