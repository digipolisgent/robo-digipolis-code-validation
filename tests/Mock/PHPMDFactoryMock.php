<?php

namespace DigipolisGent\Tests\Robo\Task\CodeValidation\Mock;

use DigipolisGent\Robo\Task\CodeValidation\Factory\PHPMD\PHPMDFactoryInterface;

class PHPMDFactoryMock implements PHPMDFactoryInterface
{

    protected static $extensions;
    protected static $ignorePatterns;
    protected static $format;
    protected static $reportFile;
    protected static $minimumPriority;
    protected static $phpmd;
    protected static $renderer;
    protected static $ruleSetFactory;

    /**
     * {@inheritdoc}
     */
    public static function create($extensions, $ignorePatterns)
    {
        if ($extensions !== static::$extensions || $ignorePatterns !== static::$ignorePatterns) {
            throw new \Exception('Factory called with invalid arguments. Expected '
                . print_r(static::$extensions, true) . ', ' . print_r(static::$ignorePatterns, true)
                . ' got ' . print_r($extensions, true) . ', ' . print_r($ignorePatterns, true));
        }
        return static::$phpmd;
    }

    /**
     * {@inheritdoc}
     */
    public static function createRenderer($format, $reportFile = null)
    {
        if ($format !== static::$format || $reportFile !== static::$reportFile) {
            throw new \Exception('Factory called with invalid arguments. Expected '
                . print_r(static::$format, true) . ', ' . print_r(static::$reportFile, true)
                . ' got ' . print_r($format, true) . ', ' . print_r($reportFile, true));
        }
        return static::$renderer;
    }

    /**
     * {@inheritdoc}
     */
    public static function createRuleSetFactory($minimumPriority)
    {
        if ($minimumPriority !== static::$minimumPriority) {
            throw new \Exception('Factory called with invalid arguments. Expected '
                . print_r(static::$minimumPriority, true)
                . ' got ' . print_r($minimumPriority, true));
        }
        return static::$ruleSetFactory;
    }

    public static function setAllowedFileExtensions($extensions)
    {
        static::$extensions = $extensions;
    }

    public static function setIgnorePatterns($ignorePatterns)
    {
        static::$ignorePatterns = $ignorePatterns;
    }

    public static function setFormat($format)
    {
        static::$format = $format;
    }

    public static function setReportFile($reportFile)
    {
        static::$reportFile = $reportFile;
    }

    public static function setMinimumPriority($minimumPriority)
    {
        static::$minimumPriority = $minimumPriority;
    }

    public static function setPhpmd($phpmd)
    {
        static::$phpmd = $phpmd;
    }

    public static function setRenderer($renderer)
    {
        static::$renderer = $renderer;
    }

    public static function setRuleSetFactory($ruleSetFactory)
    {
        static::$ruleSetFactory = $ruleSetFactory;
    }



}
