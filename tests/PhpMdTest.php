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
        $allowedFileExtensions,
        $ignorePatterns,
        $format,
        $reportFile,
        $minimumPriority
    ) {
        $phpmd = $this->getMockBuilder(\PHPMD\PHPMD::class)
            ->getMock();

        PHPMDFactoryMock::setPhpmd($phpmd);
        PHPMDFactoryMock::setAllowedFileExtensions($allowedFileExtensions);
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
        $allowedFileExtensions = ['inc', 'php', 'module'];
        $ignorePatterns = ['ignoreme'];
        $format = 'xml';
        $reportFile = 'path/to/report/file.xml';
        $rulesets = ['codesize', 'unusedcode'];
        $minimumPriority = 0;
        $mocks = $this->mockPHPMDFactory($allowedFileExtensions, $ignorePatterns, $format, $reportFile, $minimumPriority);
        $phpmd = $mocks['phpmd'];
        $phpmd->expects($this->once())->method('processFiles')
            ->with($path, $rulesets, [$mocks['renderer']], $mocks['ruleSetFactory'])
            ->willReturn(null);
        $phpmd->expects($this->once())->method('hasViolations')
            ->willReturn(false);
        $result = $this->taskPhpMd($path, $format, $allowedFileExtensions)
            ->ignorePatterns($ignorePatterns)
            ->reportFile($reportFile)
            ->minimumPriority($minimumPriority)
            ->rulesets($rulesets)
            ->phpMdFactory(PHPMDFactoryMock::class)
            ->run();

        $this->assertEquals(0, $result->getExitCode());
        $this->assertEquals('', $result->getMessage());
    }

}
