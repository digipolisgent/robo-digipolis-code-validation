<?php

namespace DigipolisGent\Robo\Task\CodeValidation\Factory\PHPMD;

use PHPMD\PHPMD;
use PHPMD\Renderer\HTMLRenderer;
use PHPMD\Renderer\TextRenderer;
use PHPMD\Renderer\XMLRenderer;
use PHPMD\RuleSetFactory;
use PHPMD\Writer\StreamWriter;

class PHPMDFactory
{
    /**
     * {@inheritdoc}
     */
    public static function create(array $allowedFileExtensions, array $ignorePatterns) {
        $phpmd = new PHPMD();
        $phpmd->setFileExtensions($allowedFileExtensions);
        $phpmd->setIgnorePattern($ignorePatterns);
        return $phpmd;
    }

    /**
     * {@inheritdoc}
     */
    public static function createRenderer($format, $reportFile = null) {
        $renderer = new XMLRenderer();
        switch ($format) {
            case 'text':
                $renderer = new TextRenderer();
                break;

            case 'html':
                $renderer = new HTMLRenderer();
                break;
            case 'xml':
            default:
                $renderer = new XMLRenderer();
                break;
        }
        $renderer->setWriter(new StreamWriter($reportFile ? $reportFile : STDOUT));
        return $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public static function createRuleSetFactory($minimumPriority) {
        $ruleSetFactory = new RuleSetFactory();
        $ruleSetFactory->setMinimumPriority($minimumPriority);
        return $ruleSetFactory;
    }
}
