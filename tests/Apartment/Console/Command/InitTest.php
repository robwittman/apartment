<?php

namespace Test\Apartment\Console\Command;

use Apartment\Console\Command\Init;
use Apartment\Console\ApartmentApplication;
use Symfony\Component\Console\Tester\CommandTester;

class InitTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $file = sys_get_temp_dir() . '/apartment.php';
        if (is_file($file)) {
            unlink($file);
        }
    }

    public function testConfigIsWritten()
    {
        $application = new ApartmentApplication('testing');
        $application->add(new Init());

        $command = $application->find('init');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'path' => sys_get_temp_dir()
        ], [
            'decorated' => false
        ]);

        $this->assertRegExp(
            '/created (.*)apartment.php(.*)/',
            $commandTester->getDisplay()
        );

        $this->assertFileExists(
            sys_get_temp_dir() . '/apartment.php',
            'Apartment configuration not existent'
        );
    }

    /**
     * @expectedException              \InvalidArgumentException
     * @expectedExceptionMessageRegExp /The file "(.*)" already exists/
     */
    public function testThrowsExceptionWhenConfigFilePresent()
    {
        touch(sys_get_temp_dir() . '/apartment.php');
        $application = new ApartmentApplication('testing');
        $application->add(new Init());

        $command = $application->find('init');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'path' => sys_get_temp_dir()
        ], [
            'decorated' => false
        ]);
    }
}
