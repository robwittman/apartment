<?php

namespace Test\Apartment\Console;

use Apartment\Console\Command\AbstractCommand;
use Apartment\Console\ApartmentApplication;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApartmentApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provider
     *
     * @param AbstractCommand $command
     * @param $result
     */
    public function testRun($command, $result)
    {
        $app = new ApartmentApplication('testing');
        $app->setAutoExit(false); // Set autoExit to false when testing
        $app->setCatchExceptions(false);

        $appTester = new ApplicationTester($app);
        $appTester->run(['command' => $command]);
        $stream = $appTester->getOutput()->getStream();
        rewind($stream);

        $this->assertRegExp($result, stream_get_contents($stream));
    }

    public function provider()
    {
        return [
            ['help', '/help \[options\] \[--\] \[<command_name>\]/']
        ];
    }
}
