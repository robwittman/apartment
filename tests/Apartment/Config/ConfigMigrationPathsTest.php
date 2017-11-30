<?php

namespace Test\Apartment\Config;

use \Apartment\Config\Config;

/**
 * Class ConfigMigrationPathsTest
 * @package Test\Apartment\Config
 * @group config
 * @covers \Apartment\Config\Config::getMigrationPaths
 */
class ConfigMigrationPathsTest extends AbstractConfigTest
{
    /**
     * @expectedException \UnexpectedValueException
     */
    public function testGetMigrationPathsThrowsExceptionForNoPath()
    {
        $config = new Config([]);
        $config->getMigrationPaths();
    }

    /**
     * Normal behavior
     */
    public function testGetMigrationPaths()
    {
        $config = new Config($this->getConfigArray());
        $this->assertEquals($this->getMigrationPaths(), $config->getMigrationPaths());
    }

    public function testGetMigrationPathConvertsStringToArray()
    {
        $values = [
            'paths' => [
                'migrations' => '/test'
            ]
        ];

        $config = new Config($values);
        $paths = $config->getMigrationPaths();

        $this->assertTrue(is_array($paths));
        $this->assertTrue(count($paths) === 1);
    }
}
