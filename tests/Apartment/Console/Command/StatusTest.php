<?php

namespace Test\Apartment\Console\Command;

use Apartment\Config\Config;
use Apartment\Config\ConfigInterface;
use Apartment\Console\Command\Status;
use Apartment\Console\ApartmentApplication;
use Apartment\Migration\Manager;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Tester\CommandTester;

class StatusTest extends \PHPUnit_Framework_TestCase
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

    /**
     * Default Test Environment
     */
    const DEFAULT_TEST_ENVIRONMENT = 'development';

    protected function setUp()
    {
        $this->config = new Config([
            'paths' => [
                'migrations' => __FILE__,
            ],
            'environments' => [
                'default_migration_table' => 'Apartmentlog',
                'default_database' => 'development',
                'development' => [
                    'adapter' => 'pgsql',
                    'host' => 'fakehost',
                    'name' => 'development',
                    'user' => '',
                    'pass' => '',
                    'port' => 5433,
                ]
            ]
        ]);

        $this->input = new ArrayInput([]);
        $this->output = new StreamOutput(fopen('php://memory', 'a', false));
    }

    public function testExecute()
    {
        $application = new ApartmentApplication('testing');
        $application->add(new Status());

        /** @var Status $command */
        $command = $application->find('status');

        // mock the manager class
        /** @var Manager|PHPUnit_Framework_MockObject_MockObject $managerStub */
        $managerStub = $this->getMockBuilder('\Apartment\Migration\Manager')
            ->setConstructorArgs([$this->config, $this->input, $this->output])
            ->getMock();
        $managerStub->expects($this->once())
                    ->method('printStatus')
                    ->with(self::DEFAULT_TEST_ENVIRONMENT, null)
                    ->will($this->returnValue(0));

        $command->setConfig($this->config);
        $command->setManager($managerStub);

        $commandTester = new CommandTester($command);
        $return = $commandTester->execute(['command' => $command->getName()], ['decorated' => false]);

        $this->assertEquals(0, $return);

        $display = $commandTester->getDisplay();
        $this->assertRegExp('/no environment specified/', $display);

        // note that the default order is by creation time
        $this->assertRegExp('/ordering by creation time/', $display);
    }

    public function testExecuteWithEnvironmentOption()
    {
        $application = new ApartmentApplication('testing');
        $application->add(new Status());

        /** @var Status $command */
        $command = $application->find('status');

        // mock the manager class
        /** @var Manager|PHPUnit_Framework_MockObject_MockObject $managerStub */
        $managerStub = $this->getMockBuilder('\Apartment\Migration\Manager')
            ->setConstructorArgs([$this->config, $this->input, $this->output])
            ->getMock();
        $managerStub->expects($this->once())
                    ->method('printStatus')
                    ->with('fakeenv', null)
                    ->will($this->returnValue(0));

        $command->setConfig($this->config);
        $command->setManager($managerStub);

        $commandTester = new CommandTester($command);
        $return = $commandTester->execute(['command' => $command->getName(), '--environment' => 'fakeenv'], ['decorated' => false]);
        $this->assertEquals(0, $return);
        $this->assertRegExp('/using environment fakeenv/', $commandTester->getDisplay());
    }

    public function testFormatSpecified()
    {
        $application = new ApartmentApplication('testing');
        $application->add(new Status());

        /** @var Status $command */
        $command = $application->find('status');

        // mock the manager class
        /** @var Manager|PHPUnit_Framework_MockObject_MockObject $managerStub */
        $managerStub = $this->getMockBuilder('\Apartment\Migration\Manager')
            ->setConstructorArgs([$this->config, $this->input, $this->output])
            ->getMock();
        $managerStub->expects($this->once())
                    ->method('printStatus')
                    ->with(self::DEFAULT_TEST_ENVIRONMENT, 'json')
                    ->will($this->returnValue(0));

        $command->setConfig($this->config);
        $command->setManager($managerStub);

        $commandTester = new CommandTester($command);
        $return = $commandTester->execute(['command' => $command->getName(), '--format' => 'json'], ['decorated' => false]);
        $this->assertEquals(0, $return);
        $this->assertRegExp('/using format json/', $commandTester->getDisplay());
    }

    public function testExecuteVersionOrderByExecutionTime()
    {
        $application = new ApartmentApplication('testing');
        $application->add(new Status());

        /** @var Status $command */
        $command = $application->find('status');

        // mock the manager class
        /** @var Manager|PHPUnit_Framework_MockObject_MockObject $managerStub */
        $managerStub = $this->getMockBuilder('\Apartment\Migration\Manager')
            ->setConstructorArgs([$this->config, $this->input, $this->output])
            ->getMock();
        $managerStub->expects($this->once())
                    ->method('printStatus')
                    ->with(self::DEFAULT_TEST_ENVIRONMENT, null)
                    ->will($this->returnValue(0));

        $this->config['version_order'] = \Apartment\Config\Config::VERSION_ORDER_EXECUTION_TIME;

        $command->setConfig($this->config);
        $command->setManager($managerStub);

        $commandTester = new CommandTester($command);
        $return = $commandTester->execute(['command' => $command->getName()], ['decorated' => false]);

        $this->assertEquals(0, $return);

        $display = $commandTester->getDisplay();
        $this->assertRegExp('/no environment specified/', $display);
        $this->assertRegExp('/ordering by execution time/', $display);
    }
}
