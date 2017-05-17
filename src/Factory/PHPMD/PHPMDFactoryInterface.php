<?php

namespace DigipolisGent\Robo\Task\CodeValidation\Factory\PHPMD;

interface PHPMDFactoryInterface
{
    /**
     * Create a new PHPMD instance.
     *
     * @param array $allowedFileExtensions
     *   Extensions without leading dot.
     * @param array $ignorePatterns
     *   List of ignore patterns.
     *
     * @return \PHPMD\PHPMD
     */
    public static function create($allowedFileExtensions, $ignorePatterns);

    /**
     * Create a PHPMD renderer.
     *
     * @param string $format
     *   The format for the render.
     * @param string $reportFile
     *   The file the renderer should write its output to.
     *
     * @return \PHPMD\AbstractRenderer
     */
    public static function createRenderer($format, $reportFile = null);

    /**
     * Create a ruleset factory.
     *
     * @param int $minimumPriority
     *   The minimum priority value.
     *
     * @return \PHPMD\RuleSetFactory
     */
    public static function createRuleSetFactory($minimumPriority);
}
