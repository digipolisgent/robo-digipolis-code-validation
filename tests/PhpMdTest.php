<?php

namespace DigipolisGent\Tests\Robo\Task\CodeValidation;

use DigipolisGent\Tests\Robo\Task\CodeValidation\Mock\PHPMDFactoryMock;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Robo\Common\CommandArguments;
use Robo\Robo;
use Robo\TaskAccessor;
use Symfony\Component\Console\Output\NullOutput;

class PhpMdTest extends \PHPUnit_Framework_TestCase implements ContainerAwareInterface
{

    use \DigipolisGent\Robo\Task\CodeValidation\loadTasks;
    use TaskAccessor;
    use ContainerAwareTrait;
    use CommandArguments;

    /**
     * Set up the Robo container so that we can create tasks in our tests.
     */
    public function setUp()
    {
        $container = Robo::createDefaultContainer(null, new NullOutput());
        $this->setContainer($container);
    }

    /**
     * Scaffold the collection builder.
     *
     * @return \Robo\Collection\CollectionBuilder
     *   The collection builder.
     */
    public function collectionBuilder()
    {
        $emptyRobofile = new \Robo\Tasks();

        return $this->getContainer()
                ->get('collectionBuilder', [$emptyRobofile]);
    }

    protected function mockPHPMDFactory(
        $extensions,
        $ignorePatterns,
        $format,
        $reportFile,
        $minimumPriority
    ) {
        $phpmd = $this->getMockBuilder(\PHPMD\PHPMD::class)
            ->getMock();

        PHPMDFactoryMock::setPhpmd($phpmd);
        PHPMDFactoryMock::setAllowedFileExtensions($extensions);
        PHPMDFactoryMock::setIgnorePatterns($ignorePatterns);

        $renderer = $this->getMockBuilder(\PHPMD\AbstractRenderer::class)
            ->getMock();

        PHPMDFactoryMock::setRenderer($renderer);
        PHPMDFactoryMock::setFormat($format);
        PHPMDFactoryMock::setReportFile($reportFile);

        $ruleSetFactory = $this->getMockBuilder(\PHPMD\RuleSetFactory::class)
            ->getMock();

        PHPMDFactoryMock::setRuleSetFactory($ruleSetFactory);
        PHPMDFactoryMock::setMinimumPriority($minimumPriority);

        return [
            'phpmd' => $phpmd,
            'renderer' => $renderer,
            'ruleSetFactory' => $ruleSetFactory,
        ];
    }

    public function testSuccess()
    {
        $path = 'path/to/code';
        $extensions = ['inc', 'php', 'module'];
        $ignorePatterns = ['ignoreme'];
        $format = 'xml';
        $reportFile = 'path/to/report/file.xml';
        $rulesets = ['codesize', 'unusedcode'];
        $minimumPriority = 0;
        $mocks = $this->mockPHPMDFactory($extensions, $ignorePatterns, $format, $reportFile, $minimumPriority);
        $phpmd = $mocks['phpmd'];
        $phpmd->expects($this->once())->method('processFiles')
            ->with($path, implode(',', $rulesets), [$mocks['renderer']], $mocks['ruleSetFactory'])
            ->willReturn(null);
        $phpmd->expects($this->once())->method('hasViolations')
            ->willReturn(false);
        $result = $this->taskPhpMd($path, $format, $extensions)
            ->ignorePatterns($ignorePatterns)
            ->reportFile($reportFile)
            ->minimumPriority($minimumPriority)
            ->rulesets($rulesets)
            ->phpMdFactory(PHPMDFactoryMock::class)
            ->run();

        $this->assertEquals(0, $result->getExitCode());
        $this->assertEquals('', $result->getMessage());
    }

    public function testFailure() {
        $path = 'path/to/code';
        $extensions = ['inc', 'php', 'module'];
        $ignorePatterns = ['ignoreme', 'ignoreme2'];
        $format = 'xml';
        $reportFile = 'path/to/report/file.xml';
        $rulesets = ['codesize', 'unusedcode'];
        $minimumPriority = 0;
        $mocks = $this->mockPHPMDFactory($extensions, $ignorePatterns, $format, $reportFile, $minimumPriority);
        $phpmd = $mocks['phpmd'];
        $phpmd->expects($this->once())->method('processFiles')
            ->with($path, implode(',', $rulesets), [$mocks['renderer']], $mocks['ruleSetFactory'])
            ->willReturn(null);
        $phpmd->expects($this->once())->method('hasViolations')
            ->willReturn(true);
        $result = $this->taskPhpMd($path, $format, $extensions)
            ->ignorePatterns($ignorePatterns)
            ->reportFile($reportFile)
            ->minimumPriority($minimumPriority)
            ->rulesets($rulesets)
            ->phpMdFactory(PHPMDFactoryMock::class)
            ->run();

        $this->assertEquals(1, $result->getExitCode());
        $this->assertEquals('PHPMD found errors. The result was written to ' . $reportFile, $result->getMessage());
    }

    public function testRuleSetMethod() {
        $path = 'path/to/code';
        $extensions = ['inc', 'php', 'module'];
        $ignorePatterns = ['ignoreme', 'ignoreme2'];
        $format = 'xml';
        $reportFile = 'path/to/report/file.xml';
        $rulesets = ['codesize', 'unusedcode'];
        $minimumPriority = 0;
        $mocks = $this->mockPHPMDFactory($extensions, $ignorePatterns, $format, $reportFile, $minimumPriority);
        $phpmd = $mocks['phpmd'];
        $phpmd->expects($this->once())->method('processFiles')
            ->with($path, implode(',', $rulesets), [$mocks['renderer']], $mocks['ruleSetFactory'])
            ->willReturn(null);
        $phpmd->expects($this->once())->method('hasViolations')
            ->willReturn(false);
        $result = $this->taskPhpMd($path, $format, $extensions)
            ->ignorePatterns($ignorePatterns)
            ->reportFile($reportFile)
            ->minimumPriority($minimumPriority)
            // Test adding the rulesets one by one.
            ->rulesets($rulesets[0])
            ->rulesets($rulesets[1])
            ->phpMdFactory(PHPMDFactoryMock::class)
            ->run();

        $this->assertEquals(0, $result->getExitCode());
        $this->assertEquals('', $result->getMessage());
    }

    public function testAllowedFileExtensionsMethod() {
        $path = 'path/to/code';
        $extensions = ['inc', 'php', 'module'];
        $ignorePatterns = ['ignoreme', 'ignoreme2'];
        $format = 'xml';
        $reportFile = 'path/to/report/file.xml';
        $rulesets = ['codesize', 'unusedcode'];
        $minimumPriority = 0;
        $mocks = $this->mockPHPMDFactory($extensions, $ignorePatterns, $format, $reportFile, $minimumPriority);
        $phpmd = $mocks['phpmd'];
        $phpmd->expects($this->once())->method('processFiles')
            ->with($path, implode(',', $rulesets), [$mocks['renderer']], $mocks['ruleSetFactory'])
            ->willReturn(null);
        $phpmd->expects($this->once())->method('hasViolations')
            ->willReturn(false);
        $result = $this->taskPhpMd($path, $format, [])
            ->ignorePatterns($ignorePatterns)
            ->reportFile($reportFile)
            ->minimumPriority($minimumPriority)

            // Test adding the allowed file extensions one by one.
            ->allowedFileExtensions($extensions[0])
            ->allowedFileExtensions([$extensions[1], $extensions[2]])
            ->rulesets($rulesets)
            ->phpMdFactory(PHPMDFactoryMock::class)
            ->run();

        $this->assertEquals(0, $result->getExitCode());
        $this->assertEquals('', $result->getMessage());
    }

    public function testIgnorePatternsMethod()
    {
        $path = 'path/to/code';
        $extensions = ['inc', 'php', 'module'];
        $ignorePatterns = ['ignoreme', 'ignoreme2'];
        $format = 'xml';
        $reportFile = 'path/to/report/file.xml';
        $rulesets = ['codesize', 'unusedcode'];
        $minimumPriority = 0;
        $mocks = $this->mockPHPMDFactory($extensions, $ignorePatterns, $format, $reportFile, $minimumPriority);
        $phpmd = $mocks['phpmd'];
        $phpmd->expects($this->once())->method('processFiles')
            ->with($path, implode(',', $rulesets), [$mocks['renderer']], $mocks['ruleSetFactory'])
            ->willReturn(null);
        $phpmd->expects($this->once())->method('hasViolations')
            ->willReturn(false);
        $result = $this->taskPhpMd($path, $format, $extensions)
            // Test adding the ignored patterns one by one.
            ->ignorePatterns($ignorePatterns[0])
            ->ignorePatterns($ignorePatterns[1])
            ->reportFile($reportFile)
            ->minimumPriority($minimumPriority)
            ->rulesets($rulesets)
            ->phpMdFactory(PHPMDFactoryMock::class)
            ->run();

        $this->assertEquals(0, $result->getExitCode());
        $this->assertEquals('', $result->getMessage());
    }

}
