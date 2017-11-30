<?php

namespace Test\Apartment\Config;

use \Apartment\Config\Config;

/**
 * Class ConfigTest
 * @package Test\Apartment\Config
 * @group config
 */
class ConfigTest extends AbstractConfigTest
{
    /**
     * @covers \Apartment\Config\Config::__construct
     * @covers \Apartment\Config\Config::getConfigFilePath
     */
    public function testConstructEmptyArguments()
    {
        $config = new Config([]);
        // this option is set to its default value when not being passed in the constructor, so we can ignore it
        unset($config['version_order']);
        $this->assertAttributeEmpty('values', $config);
        $this->assertAttributeEquals(null, 'configFilePath', $config);
        $this->assertNull($config->getConfigFilePath());
    }

    /**
     * @covers \Apartment\Config\Config::__construct
     * @covers \Apartment\Config\Config::getConfigFilePath
     */
    public function testConstructByArray()
    {
        $config = new Config($this->getConfigArray());
        $this->assertAttributeNotEmpty('values', $config);
        $this->assertAttributeEquals(null, 'configFilePath', $config);
        $this->assertNull($config->getConfigFilePath());
    }

    /**
     * @covers \Apartment\Config\Config::getEnvironments
     */
    public function testGetEnvironmentsMethod()
    {
        $config = new Config($this->getConfigArray());
        $this->assertEquals(2, count($config->getEnvironments()));
        $this->assertArrayHasKey('testing', $config->getEnvironments());
        $this->assertArrayHasKey('production', $config->getEnvironments());
    }

    /**
     * @covers \Apartment\Config\Config::hasEnvironment
     */
    public function testHasEnvironmentDoesntHave()
    {
        $config = new Config([]);
        $this->assertFalse($config->hasEnvironment('dummy'));
    }

    /**
     * @covers \Apartment\Config\Config::hasEnvironment
     */
    public function testHasEnvironmentHasOne()
    {
        $config = new Config($this->getConfigArray());
        $this->assertTrue($config->hasEnvironment('testing'));
    }

    /**
     * @covers \Apartment\Config\Config::getEnvironments
     */
    public function testGetEnvironmentsNotSet()
    {
        $config = new Config([]);
        $this->assertNull($config->getEnvironments());
    }

    /**
     * @covers \Apartment\Config\Config::getEnvironment
     */
    public function testGetEnvironmentMethod()
    {
        $config = new Config($this->getConfigArray());
        $db = $config->getEnvironment('testing');
        $this->assertEquals('sqllite', $db['adapter']);
    }

    /**
     * @covers \Apartment\Config\Config::getEnvironment
     */
    public function testHasEnvironmentMethod()
    {
        $configArray = $this->getConfigArray();
        $config = new Config($configArray);
        $this->assertTrue($config->hasEnvironment('testing'));
        $this->assertFalse($config->hasEnvironment('fakeenvironment'));
    }

    /**
     * @covers \Apartment\Config\Config::offsetGet
     * @covers \Apartment\Config\Config::offsetSet
     * @covers \Apartment\Config\Config::offsetExists
     * @covers \Apartment\Config\Config::offsetUnset
     */
    public function testArrayAccessMethods()
    {
        $config = new Config([]);
        $config['foo'] = 'bar';
        $this->assertEquals('bar', $config['foo']);
        $this->assertTrue(isset($config['foo']));
        unset($config['foo']);
        $this->assertFalse(isset($config['foo']));
    }

    /**
     * @covers \Apartment\Config\Config::offsetGet
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Identifier "foo" is not defined.
     */
    public function testUndefinedArrayAccess()
    {
        $config = new Config([]);
        $config['foo'];
    }

    /**
     * @covers \Apartment\Config\Config::getMigrationBaseClassName
     */
    public function testGetMigrationBaseClassNameGetsDefaultBaseClass()
    {
        $config = new Config([]);
        $this->assertEquals('AbstractMigration', $config->getMigrationBaseClassName());
    }

    /**
     * @covers \Apartment\Config\Config::getMigrationBaseClassName
     */
    public function testGetMigrationBaseClassNameGetsDefaultBaseClassWithNamespace()
    {
        $config = new Config([]);
        $this->assertEquals('Apartment\Migration\AbstractMigration', $config->getMigrationBaseClassName(false));
    }

    /**
     * @covers \Apartment\Config\Config::getMigrationBaseClassName
     */
    public function testGetMigrationBaseClassNameGetsAlternativeBaseClass()
    {
        $config = new Config(['migration_base_class' => 'Apartment\Migration\AlternativeAbstractMigration']);
        $this->assertEquals('AlternativeAbstractMigration', $config->getMigrationBaseClassName());
    }

    /**
     * @covers \Apartment\Config\Config::getMigrationBaseClassName
     */
    public function testGetMigrationBaseClassNameGetsAlternativeBaseClassWithNamespace()
    {
        $config = new Config(['migration_base_class' => 'Apartment\Migration\AlternativeAbstractMigration']);
        $this->assertEquals('Apartment\Migration\AlternativeAbstractMigration', $config->getMigrationBaseClassName(false));
    }

    /**
     * @covers \Apartment\Config\Config::getTemplateFile
     * @covers \Apartment\Config\Config::getTemplateClass
     */
    public function testGetTemplateValuesFalseOnEmpty()
    {
        $config = new \Apartment\Config\Config([]);
        $this->assertFalse($config->getTemplateFile());
        $this->assertFalse($config->getTemplateClass());
    }

    public function testGetAliasNoAliasesEntry()
    {
        $config = new \Apartment\Config\Config([]);
        $this->assertNull($config->getAlias('Short'));
    }

    public function testGetAliasEmptyAliasesEntry()
    {
        $config = new \Apartment\Config\Config(['aliases' => []]);
        $this->assertNull($config->getAlias('Short'));
    }

    public function testGetAliasInvalidAliasRequest()
    {
        $config = new \Apartment\Config\Config(['aliases' => ['Medium' => 'Some\Long\Classname']]);
        $this->assertNull($config->getAlias('Short'));
    }

    public function testGetAliasValidAliasRequest()
    {
        $config = new \Apartment\Config\Config(['aliases' => ['Short' => 'Some\Long\Classname']]);
        $this->assertEquals('Some\Long\Classname', $config->getAlias('Short'));
    }

    public function testGetSeedPath()
    {
        $config = new \Apartment\Config\Config(['paths' => ['seeds' => 'db/seeds']]);
        $this->assertEquals(['db/seeds'], $config->getSeedPaths());

        $config = new \Apartment\Config\Config(['paths' => ['seeds' => ['db/seeds1', 'db/seeds2']]]);
        $this->assertEquals(['db/seeds1', 'db/seeds2'], $config->getSeedPaths());
    }

    /**
     * @covers \Apartment\Config\Config::getSeedPaths
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Seeds path missing from config file
     */
    public function testGetSeedPathThrowsException()
    {
        $config = new \Apartment\Config\Config([]);
        $this->assertEquals('db/seeds', $config->getSeedPaths());
    }

    /**
     * Checks if base class is returned correctly when specified without
     * a namespace.
     *
     * @covers \Apartment\Config\Config::getMigrationBaseClassName
     */
    public function testGetMigrationBaseClassNameNoNamespace()
    {
        $config = new Config(['migration_base_class' => 'BaseMigration']);
        $this->assertEquals('BaseMigration', $config->getMigrationBaseClassName());
    }

    /**
     * Checks if base class is returned correctly when specified without
     * a namespace.
     *
     * @covers \Apartment\Config\Config::getMigrationBaseClassName
     */
    public function testGetMigrationBaseClassNameNoNamespaceNoDrop()
    {
        $config = new Config(['migration_base_class' => 'BaseMigration']);
        $this->assertEquals('BaseMigration', $config->getMigrationBaseClassName(false));
    }

    /**
     * @covers \Apartment\Config\Config::getVersionOrder
     */
    public function testGetVersionOrder()
    {
        $config = new \Apartment\Config\Config([]);
        $config['version_order'] = \Apartment\Config\Config::VERSION_ORDER_EXECUTION_TIME;
        $this->assertEquals(\Apartment\Config\Config::VERSION_ORDER_EXECUTION_TIME, $config->getVersionOrder());
    }

    /**
     * @covers \Apartment\Config\Config::isVersionOrderCreationTime
     * @dataProvider isVersionOrderCreationTimeDataProvider
     */
    public function testIsVersionOrderCreationTime($versionOrder, $expected)
    {
        // get config stub
        $configStub = $this->getMockBuilder('\Apartment\Config\Config')
            ->setMethods(['getVersionOrder'])
            ->setConstructorArgs([[]])
            ->getMock();

        $configStub->expects($this->once())
            ->method('getVersionOrder')
            ->will($this->returnValue($versionOrder));

        $this->assertEquals($expected, $configStub->isVersionOrderCreationTime());
    }

    /**
     * @covers \Apartment\Config\Config::isVersionOrderCreationTime
     */
    public function isVersionOrderCreationTimeDataProvider()
    {
        return [
            'With Creation Time Version Order' =>
            [
                \Apartment\Config\Config::VERSION_ORDER_CREATION_TIME, true
            ],
            'With Execution Time Version Order' =>
            [
                \Apartment\Config\Config::VERSION_ORDER_EXECUTION_TIME, false
            ],
        ];
    }
}
