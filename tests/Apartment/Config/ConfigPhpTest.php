<?php

namespace Test\Apartment\Config;

use \Apartment\Config\Config;

/**
 * Class ConfigPhpTest
 * @package Test\Apartment\Config
 * @group config
 */
class ConfigPhpTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Apartment\Config\Config::fromPhp
     * @covers \Apartment\Config\Config::getDefaultEnvironment
     */
    public function testFromPHPMethod()
    {
        $path = __DIR__ . '/_files';
        $config = Config::fromPhp($path . '/valid_config.php');
        $this->assertEquals('dev', $config->getDefaultEnvironment());
    }

    /**
     * @covers \Apartment\Config\Config::fromPhp
     * @covers \Apartment\Config\Config::getDefaultEnvironment
     * @expectedException \RuntimeException
     */
    public function testFromPHPMethodWithoutArray()
    {
        $path = __DIR__ . '/_files';
        $config = Config::fromPhp($path . '/config_without_array.php');
        $this->assertEquals('dev', $config->getDefaultEnvironment());
    }

    /**
     * @covers \Apartment\Config\Config::fromPhp
     * @covers \Apartment\Config\Config::getDefaultEnvironment
     * @expectedException \RuntimeException
     */
    public function testFromJSONMethodWithoutJSON()
    {
        $path = __DIR__ . '/_files';
        $config = Config::fromPhp($path . '/empty.json');
        $this->assertEquals('dev', $config->getDefaultEnvironment());
    }
}
