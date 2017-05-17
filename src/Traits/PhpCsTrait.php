<?php

namespace DigipolisGent\Robo\Task\CodeValidation\Traits;

use DigipolisGent\Robo\Task\CodeValidation\PhpCs;

trait PhpCsTrait
{
    /**
     * Creates a PhpCs task.
     *
     * @param string $dir
     *   The directory with source code to validate.
     * @param string $standard
     *   The coding standard to check.
     * @param string $extensions
     *   The file extensions to check (comma separated).
     *
     * @return \DigipolisGent\Robo\Task\CodeValidation\PhpCs
     *   The phpcs project task.
     */
    protected function taskPhpCs($dir = null, $standard = "PSR1,PSR2", $extensions = ['php','inc'])
    {
        return $this->task(PhpCs::class, $dir, $standard, $extensions);
    }
}
