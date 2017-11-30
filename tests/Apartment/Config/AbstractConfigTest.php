<?php

namespace Test\Apartment\Config;

/**
 * Class AbstractConfigTest
 * @package Test\Apartment\Config
 * @group config
 * @coversNothing
 */
abstract class AbstractConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $migrationPath = null;

    /**
     * @var string
     */
    protected $seedPath = null;

    /**
     * Returns a sample configuration array for use with the unit tests.
     *
     * @return array
     */
    public function getConfigArray()
    {
        return [
            'default' => [
                'paths' => [
                    'migrations' => '%%Apartment_CONFIG_PATH%%/testmigrations2',
                    'seeds' => '%%Apartment_CONFIG_PATH%%/db/seeds',
                ]
            ],
            'paths' => [
                'migrations' => $this->getMigrationPaths(),
                'seeds' => $this->getSeedPaths()
            ],
            'templates' => [
                'file' => '%%Apartment_CONFIG_PATH%%/tpl/testtemplate.txt',
                'class' => '%%Apartment_CONFIG_PATH%%/tpl/testtemplate.php'
            ],
            'environments' => [
                'default_migration_table' => 'Apartmentlog',
                'default_database' => 'testing',
                'testing' => [
                    'adapter' => 'sqllite',
                    'wrapper' => 'testwrapper',
                    'path' => '%%Apartment_CONFIG_PATH%%/testdb/test.db'
                ],
                'production' => [
                    'adapter' => 'mysql'
                ]
            ]
        ];
    }

    /**
     * Generate dummy migration paths
     *
     * @return string[]
     */
    protected function getMigrationPaths()
    {
        if (null === $this->migrationPath) {
            $this->migrationPath = uniqid('Apartment', true);
        }

        return [$this->migrationPath];
    }

    /**
     * Generate dummy seed paths
     *
     * @return string[]
     */
    protected function getSeedPaths()
    {
        if (null === $this->seedPath) {
            $this->seedPath = uniqid('Apartment', true);
        }

        return [$this->seedPath];
    }
}
