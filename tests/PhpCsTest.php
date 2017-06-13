<?php

namespace DigipolisGent\Tests\Robo\Task\CodeValidation;

use DigipolisGent\Robo\Task\CodeValidation\PhpCs;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Robo\Common\CommandArguments;
use Robo\Contract\ConfigAwareInterface;
use Robo\Robo;
use Robo\TaskAccessor;
use Symfony\Component\Console\Output\NullOutput;

class PhpCsTest extends \PHPUnit_Framework_TestCase implements ContainerAwareInterface, ConfigAwareInterface
{

    use \DigipolisGent\Robo\Task\CodeValidation\loadTasks;
    use TaskAccessor;
    use ContainerAwareTrait;
    use CommandArguments;
    use \Robo\Common\ConfigAwareTrait;

    /**
     * Set up the Robo container so that we can create tasks in our tests.
     */
    public function setUp()
    {
        $container = Robo::createDefaultContainer(null, new NullOutput());
        $this->setContainer($container);
        $this->setConfig(Robo::config());
        $this->getConfig()->set('digipolis.root.project', null);
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

    /**
     * Get the path to the phpcs executable.
     */
    public function getPhpCsExecutable() {
        return getcwd() . '/vendor/bin/phpcs';
    }


    /**
     * Test the default options.
     */
    public function testDefaultOptions()
    {
        $command = $this->getPhpCsExecutable() . ' --extensions=php,inc --standard=PSR1,PSR2 --report=checkstyle ' . getcwd();
        $this->assertEquals(
            $command,
            $this->taskPhpCs()->getCommand()
        );
    }

    /**
     * Test the dir option.
     */
    public function testDirOption()
    {
        $command = $this->getPhpCsExecutable() . ' --extensions=php,inc --standard=PSR1,PSR2 --report=checkstyle /path/to/dir';
        $this->assertEquals(
            $command,
            $this->taskPhpCs('/path/to/dir')->getCommand()
        );
        $this->getConfig()->set('digipolis.root.project', '/path/to/dir');
        $this->assertEquals(
            $command,
            $this->taskPhpCs('/path/to/dir')->getCommand()
        );
    }

    /**
     * Test the coding standard option.
     */
    public function testCodingStandardOption()
    {
        $command = $this->getPhpCsExecutable() . ' --extensions=php,inc --standard=myStandard --report=checkstyle ' . getcwd();
        $this->assertEquals(
            $command,
            $this->taskPhpCs(null, 'myStandard')->getCommand()
        );
    }

    /**
     * Test the extensions option.
     */
    public function testExtensionsOption()
    {
        $command = $this->getPhpCsExecutable() . ' --extensions=php,inc,module --standard=PSR1,PSR2 --report=checkstyle ' . getcwd();
        $this->assertEquals(
            $command,
            $this->taskPhpCs(null, 'PSR1,PSR2', ['php', 'inc', 'module'])->getCommand()
        );
    }

    /**
     * Test the ignore option.
     */
    public function testIgnoreOption()
    {
        $command = $this->getPhpCsExecutable() . ' --extensions=php,inc --ignore=vendor --standard=PSR1,PSR2 --report=checkstyle ' . getcwd();
        $this->assertEquals(
            $command,
            $this->taskPhpCs()->ignore('vendor')->getCommand()
        );

        $command = $this->getPhpCsExecutable() . ' --extensions=php,inc --ignore=vendor,tests --standard=PSR1,PSR2 --report=checkstyle ' . getcwd();
        $this->assertEquals(
            $command,
            $this->taskPhpCs()->ignore('vendor')->ignore('tests')->getCommand()
        );

        $command = $this->getPhpCsExecutable() . ' --extensions=php,inc --ignore=vendor,tests --standard=PSR1,PSR2 --report=checkstyle ' . getcwd();
        $this->assertEquals(
            $command,
            $this->taskPhpCs()->ignore(['vendor', 'tests'])->getCommand()
        );
    }

    /**
     * Test the report type option.
     */
    public function testReportTypeOption()
    {
        /*
            'report-file' => null,*/
        $command = $this->getPhpCsExecutable() . ' --extensions=php,inc --standard=PSR1,PSR2 --report=json ' . getcwd();
        $this->assertEquals(
            $command,
            $this->taskPhpCs()->reportType('json')->getCommand()
        );
    }

    /**
     * Test the report file option.
     */
    public function testReportFileOption()
    {
        /*
            'report-file' => null,*/
        $command = $this->getPhpCsExecutable() . ' --extensions=php,inc --standard=PSR1,PSR2 --report=checkstyle --report-file=report.xml ' . getcwd();
        $this->assertEquals(
            $command,
            $this->taskPhpCs()->reportFile('report.xml')->getCommand()
        );
    }

}
