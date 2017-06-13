<?php

namespace DigipolisGent\Tests\Robo\Task\CodeValidation;

use DigipolisGent\Robo\Task\CodeValidation\Factory\PHPMD\PHPMDFactory;
use PHPMD\PHPMD;



class PhpMdFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate() {
        $extensions = ['inc', 'php'];
        $ignore = ['vendor/*'];
        $phpmd = PHPMDFactory::create($extensions, $ignore);
        $this->assertInstanceOf(PHPMD::class, $phpmd);
        $this->assertEquals($extensions, $phpmd->getFileExtensions());
        $this->assertContains('vendor/*', $phpmd->getIgnorePattern());
    }

    public function testCreateRenderer() {
        $types = [
            'xml' => \PHPMD\Renderer\XMLRenderer::class,
            'html' => \PHPMD\Renderer\HTMLRenderer::class,
            'text' => \PHPMD\Renderer\TextRenderer::class,
            'unknown' => \PHPMD\Renderer\XMLRenderer::class,
        ];
        foreach ($types as $format => $class) {
            $renderer = PHPMDFactory::createRenderer($format);
            $this->assertInstanceOf($class, $renderer);
            $this->assertInstanceOf(\PHPMD\Writer\StreamWriter::class, $renderer->getWriter());
        }
    }

    public function testCreateRuleSetFactory() {
        $priority = mt_rand(0, 10);
        $factory = PHPMDFactory::createRuleSetFactory($priority);
        $this->assertInstanceOf(\PHPMD\RuleSetFactory::class, $factory);

    }
}
