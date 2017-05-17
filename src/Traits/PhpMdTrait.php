<?php

namespace DigipolisGent\Robo\Task\CodeValidation\Traits;

use DigipolisGent\Robo\Task\CodeValidation\PhpMd;

trait PhpMdTrait
{
    /**
     * Creates a PHPMD task.
     *
     * @param string $dir
     *   A php source code filename or directory.
     * @param string $format
     *   The format for the report.
     * @param array $allowedFileExentsions
     *   List of valid file extensions for analyzed files.
     *
     * @return \DigipolisGent\Robo\Task\CodeValidation\PhpMd
     *   The phpmd task.
     */
    protected function taskPhpMd($dir = null, $format = 'xml', $allowedFileExentsions = ['php', 'inc'])
    {
        return $this->task(PhpMd::class, $dir, $format, $allowedFileExentsions);
    }
}
