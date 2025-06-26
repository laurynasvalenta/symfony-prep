<?php

declare(strict_types=1);

namespace App\Service\Miscellaneous;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Service for cloning directories using the Symfony Filesystem component.
 */
class DirectoryCloner
{
    public function __construct(
        private Filesystem $filesystem
    ) {
    }

    /**
     * Clones a directory using the Filesystem component.
     */
    public function cloneDirectory(string $sourceDir, string $targetDir): void
    {
        $this->filesystem->mirror($sourceDir, $targetDir);
    }
} 