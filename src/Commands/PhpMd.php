<?php

namespace DigipolisGent\Robo\Task\CodeValidation\Commands;

trait PhpMd
{

    use \DigipolisGent\Robo\Task\CodeValidation\Traits\PhpMdTrait;

    public function digipolisPhpMd(
        $opts = [
            'dir' => null,
            'format' => 'xml',
            'extensions' => 'inc,php',
            'ignore' => null,
            'minimum-priority' => null,
            'report-file' => null,
            'rulesets' => null,
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
        $task = $this->taskPhpMd($opts['dir'], $opts['format'], explode(',', $opts['extensions']));
        if ($opts['ignore']) {
            $task->ignorePatterns(explode(',', $opts['ignore']));
        }
        if (!is_null($opts['minimum-priority'])) {
            $task->minimumPriority($opts['minimum-priority']);
        }
        if ($opts['report-file']) {
            $task->reportFile($opts['report-file']);
        }
        if ($opts['rulesets']) {
            $task->rulesets(explode(',', $opts['rulesets']));
        }
        return $task->run();
    }
}
