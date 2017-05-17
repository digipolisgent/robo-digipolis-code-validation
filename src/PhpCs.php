<?php

namespace DigipolisGent\Robo\Task\CodeValidation;

use Robo\Common\ExecCommand;
use Robo\Contract\CommandInterface;
use Robo\Task\BaseTask;

class PhpCs extends BaseTask implements CommandInterface
{
    use ExecCommand;
    /**
     * The file extensions to validate.
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * The directory in which to fine the files to validate.
     *
     * @var string
     */
    protected $dir;

    /**
     * The coding standard to validate against.
     *
     * @var string
     */
    protected $standard;

    /**
     * The report type to output by phpcs.
     *
     * @var string
     */
    protected $reportType = 'checkstyle';

    /**
     * The path to the report file to output.
     *
     * @var bool
     */
    protected $reportFile;

    /**
     * Paths or files to ignore.
     *
     * @var array
     */
    protected $ignore = [];


    /**
     * Create a new PhpCs task.
     *
     * @param string $dir
     *   The directory with source code to validate.
     * @param string $standard
     *   The coding standard to check.
     * @param array $extensions
     *   The file extensions to check.
     */
    public function __construct($dir = null, $standard = "PSR1,PSR2", $extensions = ["php", "inc"])
    {
        $this->dir = $dir;
        $this->standard = $standard;
        $this->extensions = $extensions;
    }

    /**
     * Add a path or file to ignore.
     *
     * @param string|array $ignore
     *   The path or paths to ignore.
     *
     * @return $this
     */
    public function ignore($ignore)
    {
        if (!is_array($ignore)) {
            $ignore = [$ignore];
        }
        $this->ignore = array_unique(array_merge($this->ignore, $ignore));

        return $this;
    }

    /**
     * Set the type of report to generate.
     *
     * @param string $type
     *   The type of report to generate.
     *
     * @return $this
     */
    public function reportType($type)
    {
        $this->reportType = $type;

        return $this;
    }

    /**
     * Set the file to write the report to.
     *
     * @param string $file
     *   The path to the file to write the report to.
     *
     * @return $this
     */
    public function reportFile($file)
    {
        $this->reportFile = $file;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        return $this->executeCommand($this->getCommand());
    }

    /**
     * {@inheritdoc}
     */
    public function getCommand()
    {

        if (is_null($this->dir)) {
            $projectRoot = $this->getConfig()->get('digipolis.root.project', null);
            $this->dir = is_null($projectRoot)
                ? getcwd()
                : $projectRoot;
        }
        $command = $this->findExecutable('phpcs') . ' ';
        if ($this->extensions) {
            $command .= '--extensions=' . implode(',', $this->extensions) . ' ';
        }
        if ($this->ignore) {
            $command .= '--ignore=' . implode(',', $this->ignore) . ' ';
        }
        if ($this->standard) {
            $command .= '--standard=' . $this->standard . ' ';
        }
        if ($this->reportType) {
            $command .= '--report=' . $this->reportType . ' ';
        }
        if ($this->reportFile) {
            $command .= '--report-file=' . $this->reportFile . ' ';
        }
        $command .= $this->dir;
        return $command;
    }
}
