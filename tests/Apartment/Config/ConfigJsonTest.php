<?php

namespace Test\Apartment\Config;

use \Apartment\Config\Config;

/**
 * Class ConfigJsonTest
 * @package Test\Apartment\Config
 * @group config
 */
class ConfigJsonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \Apartment\Config\Config::fromJson
     */
    public function testFromJSONMethod()
    {
        $path = __DIR__ . '/_files';
        $config = Config::fromJson($path . '/valid_config.json');
        $this->assertEquals('dev', $config->getDefaultEnvironment());
    }

    /**
     * @covers \Apartment\Config\Config::fromJson
     * @expectedException \RuntimeException
     */
    public function testFromJSONInvalidJson()
    {
        $path = __DIR__ . '/_files';
        Config::fromJson($path . '/invalid.json');
    }
}
