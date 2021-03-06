<?php

namespace DigipolisGent\Robo\Task\CodeValidation;

use DigipolisGent\Robo\Task\CodeValidation\Factory\PHPMD\PHPMDFactory;
use DigipolisGent\Robo\Task\CodeValidation\Factory\PHPMD\PHPMDFactoryInterface;
use PHPMD\AbstractRule;
use Robo\Task\BaseTask;

class PhpMd extends BaseTask
{

    /**
     * A php source code filename or directory.
     *
     * @var string
     */
    protected $dir;

    /**
     * The rule-set filenames or identifier.
     *
     * @var array
     */
    protected $rulesets = [];

    /**
     * The minimum priority for rules to load.
     *
     * @var integer
     */
    protected $minimumPriority = AbstractRule::LOWEST_PRIORITY;

    /**
     * List of valid file extensions for analyzed files.
     *
     * @var array
     */
    protected $extensions;

    /**
     * List of exclude directory patterns.
     *
     * @var array
     */
    protected $ignorePatterns = [];

    /**
     * The format for the report.
     *
     * @var string
     */
    protected $format;

    /**
     * The output file for the report.
     *
     * @var string
     */
    protected $reportFile;

    /**
     * The PHPMDFactory class.
     *
     * @var string
     */
    protected $phpMdFactory = PHPMDFactory::class;

    /**
     * Whether or not to return an exit code > 0 when PHPMD found violations.
     *
     * @var bool
     */
    protected $failOnViolations = true;

    /**
     * Creates a PHPMD task.
     *
     * @param string $dir
     *   A php source code filename or directory.
     * @param string $format
     *   The format for the report.
     * @param array $extensions
     *   List of valid file extensions for analyzed files.
     */
    public function __construct($dir = null, $format = 'xml', $extensions = [])
    {
        $this->dir = is_null($dir) ? getcwd() : $dir;
        $this->format = $format;
        $this->extensions = $extensions;
    }

    /**
     * Set the directory to parse.
     *
     * @param string $dir
     *   The directory to parse.
     *
     * @return $this
     */
    public function dir($dir)
    {
        $this->dir = $dir;

        return $this;
    }

    /**
     * Sets the minimum rule priority.
     *
     * @param integer $minimumPriority
     *   The minimum rule priority.
     *
     * @return $this
     */
    public function minimumPriority($minimumPriority)
    {
        $this->minimumPriority = $minimumPriority;

        return $this;
    }

    /**
     * Sets the rule-sets.
     *
     * @param array|string $ruleSetFileNames
     *   Array of rule-set filenames or identifiers.
     *
     * @return $this
     */
    public function rulesets($ruleSetFileNames)
    {
        if (!is_array($ruleSetFileNames)) {
            $ruleSetFileNames = [$ruleSetFileNames];
        }
        $this->rulesets = array_unique(array_merge($this->rulesets, $ruleSetFileNames));

        return $this;
    }

    /**
     * Sets a list of filename extensions for valid php source code files.
     *
     * @param array|string $fileExtensions
     *   List of valid file extensions without leading dot.
     *
     * @return $this
     */
    public function allowedFileExtensions($fileExtensions)
    {
        if (!is_array($fileExtensions)) {
            $fileExtensions = [$fileExtensions];
        }
        $this->extensions = array_unique(array_merge($this->extensions, $fileExtensions));

        return $this;
    }

    /**
     * Sets a list of ignore patterns that is used to exclude directories from
     * the source analysis.
     *
     * @param array|string $ignorePatterns
     *   List of ignore patterns.
     *
     * @return $this
     */
    public function ignorePatterns($ignorePatterns)
    {
        if (!is_array($ignorePatterns)) {
            $ignorePatterns = [$ignorePatterns];
        }
        $this->ignorePatterns = array_unique(array_merge($this->ignorePatterns, $ignorePatterns));

        return $this;
    }

    /**
     * Set the report format.
     *
     * @param string $format
     *   The report format.
     *
     * @return $this
     */
    public function format($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Set the report file.
     *
     * @param string $reportFile
     *   The report file.
     * @return $this
     */
    public function reportFile($reportFile)
    {
        $this->reportFile = $reportFile;

        return $this;
    }

    /**
     * Set whether or not to fail on violations.
     *
     * @param bool $failOnViolations
     *   Whether or not to fail on violations.
     *
     * @return $this
     */
    public function failOnViolations($failOnViolations = true)
    {
        $this->failOnViolations = $failOnViolations;

        return $this;
    }

    /**
     * Set the PHPMD factory class.
     *
     * @param string $class
     *   The factory class.
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     *   When the class is not an instance of
     *   \DigipolisGent\Robo\Task\CodeValidation\Factory\PHPMD\PHPMDFactoryInterface.
     */
    public function phpMdFactory($class)
    {
        if (!is_subclass_of($class, PHPMDFactoryInterface::class)) {
            throw new \InvalidArgumentException(sprintf(
                'PHPMD Factory %s does not implement %s.',
                $class,
                PHPMDFactoryInterface::class
            ));
        }
        $this->phpMdFactory = $class;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if (empty($this->extensions)) {
            $this->extensions = ['php', 'inc'];
        }
        $phpmd = call_user_func(
            [$this->phpMdFactory, 'create'],
            $this->extensions,
            $this->ignorePatterns
        );
        $ruleSetFactory = call_user_func(
            [$this->phpMdFactory, 'createRuleSetFactory'],
            $this->minimumPriority
        );
        $renderer = call_user_func(
            [$this->phpMdFactory, 'createRenderer'],
            $this->format,
            $this->reportFile
        );

        if (empty($this->rulesets)) {
            $this->rulesets = ['codesize', 'unusedcode'];
        }
        $phpmd->processFiles(
            $this->dir,
            implode(',', $this->rulesets),
            [$renderer],
            $ruleSetFactory
        );
        $error = 'PHPMD found errors.';
        if ($this->reportFile) {
            $error .= ' The result was written to ' . $this->reportFile;
        }
        return $phpmd->hasViolations() && $this->failOnViolations
            ? \Robo\Result::error($this, $error)
            : \Robo\Result::success($this, $phpmd->hasViolations() ? $error : '');
    }
}
