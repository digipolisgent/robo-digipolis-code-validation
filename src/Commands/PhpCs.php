<?php

namespace DigipolisGent\Robo\Task\CodeValidation\Commands;

trait PhpCs
{

    use \DigipolisGent\Robo\Task\CodeValidation\Traits\PhpCsTrait;

    public function digipolisPhpCs(
        $opts = [
            'dir' => null,
            'standard' => 'PSR1,PSR2',
            'extensions' => 'inc,php',
            'ignore' => null,
            'report-type' => 'checkstyle',
            'report-file' => null,
        ]
    ) {
        if (is_callable([$this, 'readProperties'])) {
            $this->readProperties();
            if (!$opts['dir']) {
                $opts['dir'] = $this->getConfig()->get('digipolis.root.project', null);
            }
        }
        if (!$opts['dir']) {
            $opts['dir'] = getcwd();
        }
        $task = $this->taskPhpCs($opts['dir'], $opts['standard'], exlode(',', $opts['extensions']));
        $task->reportType($opts['report-type']);
        if ($opts['report-file']) {
            $task->reportFile($opts['report-file']);
        }
        if ($opts['ignore']) {
            $task->ignore(explode(',', $opts['ignore']));
        }
        return $task->run();
    }
}
