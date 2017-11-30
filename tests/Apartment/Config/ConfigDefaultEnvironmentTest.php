<?php

namespace Test\Apartment\Config;

use \Apartment\Config\Config;

/**
 * Class ConfigDefaultEnvironmentTest
 * @package Test\Apartment\Config
 * @group config
 * @covers \Apartment\Config\Config::getDefaultEnvironment
 */
class ConfigDefaultEnvironmentTest extends AbstractConfigTest
{
    public function testGetDefaultEnvironment()
    {
        // test with the config array
        $configArray = $this->getConfigArray();
        $config = new Config($configArray);
        $this->assertEquals('testing', $config->getDefaultEnvironment());
    }

    public function testConfigReplacesTokensWithEnvVariables()
    {
        $_SERVER['Apartment_DBHOST'] = 'localhost';
        $_SERVER['Apartment_DBNAME'] = 'productionapp';
        $_SERVER['Apartment_DBUSER'] = 'root';
        $_SERVER['Apartment_DBPASS'] = 'ds6xhj1';
        $_SERVER['Apartment_DBPORT'] = '1234';
        $path = __DIR__ . '/_files';
        $config = Config::fromYaml($path . '/external_variables.yml');
        $env = $config->getEnvironment($config->getDefaultEnvironment());
        $this->assertEquals('localhost', $env['host']);
        $this->assertEquals('productionapp', $env['name']);
        $this->assertEquals('root', $env['user']);
        $this->assertEquals('ds6xhj1', $env['pass']);
        $this->assertEquals('1234', $env['port']);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The environment configuration (read from $Apartment_ENVIRONMENT) for 'conf-test' is missing
     */
    public function testGetDefaultEnvironmentOverridenByEnvButNotSet()
    {
        // set dummy
        $dummyEnv = 'conf-test';
        putenv('Apartment_ENVIRONMENT=' . $dummyEnv);

        try {
            $config = new Config([]);
            $config->getDefaultEnvironment();
        } catch (\Exception $e) {
            // reset back to normal
            putenv('Apartment_ENVIRONMENT=');

            // throw again in order to finish test
            throw $e;
        }
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Could not find a default environment
     */
    public function testGetDefaultEnvironmentOverridenFailedToFind()
    {
        // set empty env var
        putenv('Apartment_ENVIRONMENT=');

        $config = new Config([]);
        $config->getDefaultEnvironment();
    }
}
