<?php

namespace Test\Apartment\Db\Adapter;

use Apartment\Db\Adapter\AdapterFactory;

class AdapterFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Apartment\Db\Adapter\AdapterFactory
     */
    private $factory;

    public function setUp()
    {
        $this->factory = AdapterFactory::instance();
    }

    public function tearDown()
    {
        unset($this->factory);
    }

    public function testInstanceIsFactory()
    {
        $this->assertInstanceOf('Apartment\Db\Adapter\AdapterFactory', $this->factory);
    }

    public function testRegisterAdapter()
    {
        // AdapterFactory::getClass is protected, work around it to avoid
        // creating unnecessary instances and making the test more complex.
        $method = new \ReflectionMethod(get_class($this->factory), 'getClass');
        $method->setAccessible(true);

        $adapter = $method->invoke($this->factory, 'mysql');
        $this->factory->registerAdapter('test', $adapter);

        $this->assertEquals($adapter, $method->invoke($this->factory, 'test'));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Adapter class "Test\Apartment\Db\Adapter\AdapterFactoryTest" must implement Apartment\Db\Adapter\AdapterInterface
     */
    public function testRegisterAdapterFailure()
    {
        $adapter = get_class($this);
        $this->factory->registerAdapter('test', $adapter);
    }

    public function testGetAdapter()
    {
        $adapter = $this->factory->getAdapter('mysql', []);

        $this->assertInstanceOf('Apartment\Db\Adapter\MysqlAdapter', $adapter);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Adapter "bad" has not been registered
     */
    public function testGetAdapterFailure()
    {
        $this->factory->getAdapter('bad', []);
    }

    public function testRegisterWrapper()
    {
        // WrapperFactory::getClass is protected, work around it to avoid
        // creating unnecessary instances and making the test more complex.
        $method = new \ReflectionMethod(get_class($this->factory), 'getWrapperClass');
        $method->setAccessible(true);

        $wrapper = $method->invoke($this->factory, 'proxy');
        $this->factory->registerWrapper('test', $wrapper);

        $this->assertEquals($wrapper, $method->invoke($this->factory, 'test'));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Wrapper class "Test\Apartment\Db\Adapter\AdapterFactoryTest" must be implement Apartment\Db\Adapter\WrapperInterface
     */
    public function testRegisterWrapperFailure()
    {
        $wrapper = get_class($this);
        $this->factory->registerWrapper('test', $wrapper);
    }

    private function getAdapterMock()
    {
        return $this->getMockBuilder('Apartment\Db\Adapter\AdapterInterface')->getMock();
    }

    public function testGetWrapper()
    {
        $wrapper = $this->factory->getWrapper('prefix', $this->getAdapterMock());

        $this->assertInstanceOf('Apartment\Db\Adapter\TablePrefixAdapter', $wrapper);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Wrapper "nope" has not been registered
     */
    public function testGetWrapperFailure()
    {
        $this->factory->getWrapper('nope', $this->getAdapterMock());
    }
}
