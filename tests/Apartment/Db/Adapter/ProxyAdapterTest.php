<?php

namespace Test\Apartment\Db\Adapter;

use Apartment\Db\Adapter\PdoAdapter;
use Apartment\Db\Adapter\ProxyAdapter;
use Apartment\Db\Table;
use Apartment\Db\Table\ForeignKey;
use Apartment\Db\Table\Index;
use Apartment\Migration\IrreversibleMigrationException;

class ProxyAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Apartment\Db\Adapter\ProxyAdapter
     */
    private $adapter;

    public function setUp()
    {
        $stub = $this->getMockBuilder('\Apartment\Db\Adapter\PdoAdapter')
            ->setConstructorArgs([[]])
            ->getMock();

        $this->adapter = new ProxyAdapter($stub);
    }

    public function tearDown()
    {
        unset($this->adapter);
    }

    public function testProxyAdapterCanInvertCreateTable()
    {
        $table = new \Apartment\Db\Table('atable');
        $this->adapter->createTable($table);

        $commands = $this->adapter->getInvertedCommands();
        $this->assertEquals('dropTable', $commands[0]['name']);
        $this->assertEquals('atable', $commands[0]['arguments'][0]);
    }

    public function testProxyAdapterCanInvertRenameTable()
    {
        $this->adapter->renameTable('oldname', 'newname');

        $commands = $this->adapter->getInvertedCommands();
        $this->assertEquals('renameTable', $commands[0]['name']);
        $this->assertEquals('newname', $commands[0]['arguments'][0]);
        $this->assertEquals('oldname', $commands[0]['arguments'][1]);
    }

    public function testProxyAdapterCanInvertAddColumn()
    {
        $table = new \Apartment\Db\Table('atable');
        $column = new \Apartment\Db\Table\Column();
        $column->setName('acolumn');

        $this->adapter->addColumn($table, $column);

        $commands = $this->adapter->getInvertedCommands();
        $this->assertEquals('dropColumn', $commands[0]['name']);
        $this->assertEquals('atable', $commands[0]['arguments'][0]);
        $this->assertContains('acolumn', $commands[0]['arguments'][1]);
    }

    public function testProxyAdapterCanInvertRenameColumn()
    {
        $this->adapter->renameColumn('atable', 'oldname', 'newname');

        $commands = $this->adapter->getInvertedCommands();
        $this->assertEquals('renameColumn', $commands[0]['name']);
        $this->assertEquals('atable', $commands[0]['arguments'][0]);
        $this->assertEquals('newname', $commands[0]['arguments'][1]);
        $this->assertEquals('oldname', $commands[0]['arguments'][2]);
    }

    public function testProxyAdapterCanInvertAddIndex()
    {
        $table = new \Apartment\Db\Table('atable');
        $index = new \Apartment\Db\Table\Index();
        $index->setType(\Apartment\Db\Table\Index::INDEX)
              ->setColumns(['email']);

        $this->adapter->addIndex($table, $index);

        $commands = $this->adapter->getInvertedCommands();
        $this->assertEquals('dropIndex', $commands[0]['name']);
        $this->assertEquals('atable', $commands[0]['arguments'][0]);
        $this->assertContains('email', $commands[0]['arguments'][1]);
    }

    public function testProxyAdapterCanInvertAddForeignKey()
    {
        $table = new \Apartment\Db\Table('atable');
        $refTable = new \Apartment\Db\Table('refTable');
        $fk = new \Apartment\Db\Table\ForeignKey();
        $fk->setReferencedTable($refTable)
           ->setColumns(['ref_table_id'])
           ->setReferencedColumns(['id']);

        $this->adapter->addForeignKey($table, $fk);

        $commands = $this->adapter->getInvertedCommands();
        $this->assertEquals('dropForeignKey', $commands[0]['name']);
        $this->assertEquals('atable', $commands[0]['arguments'][0]);
        $this->assertContains('ref_table_id', $commands[0]['arguments'][1]);
    }

    /**
     * @expectedException \Apartment\Migration\IrreversibleMigrationException
     * @expectedExceptionMessage Cannot reverse a "createDatabase" command
     */
    public function testGetInvertedCommandsThrowsExceptionForIrreversibleCommand()
    {
        $this->adapter->recordCommand('createDatabase', ['testdb']);
        $this->adapter->getInvertedCommands();
    }
}
