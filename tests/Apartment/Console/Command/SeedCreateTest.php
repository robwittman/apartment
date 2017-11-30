<?php

namespace Test\Apartment\Console\Command;

use Apartment\Config\Config;
use Apartment\Config\ConfigInterface;
use Apartment\Console\Command\SeedCreate;
use Apartment\Console\ApartmentApplication;
use Apartment\Migration\Manager;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Tester\CommandTester;

class SeedCreateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ConfigInterface|array
     */
    protected $config = [];

    /**
     * @var InputInterface $input
     */
    protected $input;

    /**
     * @var OutputInterface $output
     */
    protected $output;

    protected function setUp()
    {
        $this->config = new Config([
            'paths' => [
                'migrations' => sys_get_temp_dir(),
                'seeds' => sys_get_temp_dir(),
            ],
            'environments' => [
                'default_migration_table' => 'Apartmentlog',
                'default_database' => 'development',
                'development' => [
                    'adapter' => 'mysql',
                    'host' => 'fakehost',
                    'name' => 'development',
                    'user' => '',
                    'pass' => '',
                    'port' => 3006,
                ]
            ]
        ]);

        $this->input = new ArrayInput([]);
        $this->output = new StreamOutput(fopen('php://memory', 'a', false));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The file "MyDuplicateSeeder.php" already exists
     */
    public function testExecute()
    {
        $application = new ApartmentApplication('testing');
        $application->add(new SeedCreate());

        /** @var SeedCreate $command */
        $command = $application->find('seed:create');

        // mock the manager class
        /** @var Manager|PHPUnit_Framework_MockObject_MockObject $managerStub */
        $managerStub = $this->getMockBuilder('\Apartment\Migration\Manager')
            ->setConstructorArgs([$this->config, $this->input, $this->output])
            ->getMock();

        $command->setConfig($this->config);
        $command->setManager($managerStub);

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'name' => 'MyDuplicateSeeder'], ['decorated' => false]);
        $commandTester->execute(['command' => $command->getName(), 'name' => 'MyDuplicateSeeder'], ['decorated' => false]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The seed class name "badseedname" is invalid. Please use CamelCase format
     */
    public function testExecuteWithInvalidClassName()
    {
        $application = new ApartmentApplication('testing');
        $application->add(new SeedCreate());

        /** @var SeedCreate $command */
        $command = $application->find('seed:create');

        // mock the manager class
        /** @var Manager|PHPUnit_Framework_MockObject_MockObject $managerStub */
        $managerStub = $this->getMockBuilder('\Apartment\Migration\Manager')
            ->setConstructorArgs([$this->config, $this->input, $this->output])
            ->getMock();

        $command->setConfig($this->config);
        $command->setManager($managerStub);

        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName(), 'name' => 'badseedname'], ['decorated' => false]);
    }
}
