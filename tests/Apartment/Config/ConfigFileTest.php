<?php

namespace Test\Apartment\Config;

use Apartment\Console\Command\AbstractCommand;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

class ConfigFileTest extends \PHPUnit_Framework_TestCase
{
    private $previousDir;

    private $baseDir;

    public function setUp()
    {
        $this->previousDir = getcwd();
        $this->baseDir = realpath(__DIR__ . '/_rootDirectories');
    }

    public function tearDown()
    {
        chdir($this->previousDir);
    }

    /**
     * Test workingContext
     *
     * @dataProvider workingProvider
     *
     * @param $input
     * @param $dir
     * @param $expectedFile
     */
    public function testWorkingGetConfigFile($input, $dir, $expectedFile)
    {
        $foundPath = $this->runLocateFile($input, $dir);
        $expectedPath = $this->baseDir . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $expectedFile;

        $this->assertEquals($foundPath, $expectedPath);
    }

    /**
     * Test workingContext
     *
     * @dataProvider notWorkingProvider
     *
     * @param $input
     * @param $dir
     * @expectedException \InvalidArgumentException
     */
    public function testNotWorkingGetConfigFile($input, $dir)
    {
        $this->runLocateFile($input, $dir);
    }

    /**
     * Do the locateFile Action
     *
     * @param $arg
     * @param $dir
     * @return string
     */
    protected function runLocateFile($arg, $dir)
    {
        chdir($this->baseDir . '/' . $dir);
        $definition = new InputDefinition([new InputOption('configuration')]);
        $input = new ArgvInput([], $definition);
        if ($arg) {
            $input->setOption('configuration', $arg);
        }
        $command = new VoidCommand('void');

        return $command->locateConfigFile($input);
    }

    /**
     * Working cases
     *
     *
     * @return array
     */
    public function workingProvider()
    {
        return [
            //explicit yaml
            ['apartment.yml', 'OnlyYaml', 'apartment.yml'],
            //implicit with all choice
            [null, 'all', 'apartment.php'],
            //implicit with no php choice
            [null, 'noPhp', 'apartment.json'],
            //implicit with only yaml choice
            [null, 'OnlyYaml', 'apartment.yml'],
            //explicit Php
            ['apartment.php', 'all', 'apartment.php'],
            //explicit json
            ['apartment.json', 'all', 'apartment.json'],
        ];
    }

    /**
     * Not working cases
     *
     * @return array
     */
    public function notWorkingProvider()
    {
        return [
            //no valid file available
            [null, 'NoValidFile'],
            //called file not available
            ['apartment.yml', 'noYaml'],
            ['apartment.json', 'OnlyYaml'],
            ['apartment.php', 'OnlyYaml'],
        ];
    }
}

/**
 * Class VoidCommand : used to expose locateConfigFile To testing
 *
 * @package Test\Apartment\Config
 */
class VoidCommand extends AbstractCommand
{
    public function locateConfigFile(InputInterface $input)
    {
        return parent::locateConfigFile($input);
    }
}
