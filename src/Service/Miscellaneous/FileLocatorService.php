<?php

declare(strict_types=1);

namespace App\Service\Miscellaneous;

use Symfony\Component\Finder\Finder;

/**
 * Service demonstrating the Symfony Finder component functionality.
 */
class FileLocatorService
{
    /**
     * Demonstrates multiple filtering methods of the Finder component in a single call.
     * 
     * @param string $directory The directory to search in
     * @return array List of found files
     */
    public function findFilesWithMultipleFilters(string $directory): array
    {
        $finder = new Finder();
        $finder->in($directory)
               ->files()
               ->name('*.txt')
               ->notName('file2.txt')
               ->contains('content')
               ->size('>10');

        $results = [];
        foreach ($finder as $file) {
            $results[] = $file->getRelativePathname();
        }

        return $results;
    }
} 