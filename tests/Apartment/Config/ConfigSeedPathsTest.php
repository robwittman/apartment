<?php

namespace Test\Apartment\Config;

use \Apartment\Config\Config;

/**
 * Class ConfigSeedPathsTest
 * @package Test\Apartment\Config
 * @group config
 * @covers \Apartment\Config\Config::getSeedPaths
 */
class ConfigSeedPathsTest extends AbstractConfigTest
{
    /**
     * @expectedException \UnexpectedValueException
     */
    public function testGetSeedPathsThrowsExceptionForNoPath()
    {
        $config = new Config([]);
        $config->getSeedPaths();
    }

    /**
     * Normal behavior
     */
    public function testGetSeedPaths()
    {
        $config = new Config($this->getConfigArray());
        $this->assertEquals($this->getSeedPaths(), $config->getSeedPaths());
    }

    public function testGetSeedPathConvertsStringToArray()
    {
        $values = [
            'paths' => [
                'seeds' => '/test'
            ]
        ];

        $config = new Config($values);
        $paths = $config->getSeedPaths();

        $this->assertTrue(is_array($paths));
        $this->assertTrue(count($paths) === 1);
    }
}
