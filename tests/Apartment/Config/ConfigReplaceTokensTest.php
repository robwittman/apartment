<?php

namespace Test\Apartment\Config;

use Apartment\Config\Config;

/**
 * Class ConfigReplaceTokensTest
 * @package Test\Apartment\Config
 * @group config
 */
class ConfigReplaceTokensTest extends AbstractConfigTest
{
    /**
     * Data to be saved to $_SERVER and checked later
     * @var array
     */
    protected static $server = [
        'Apartment_TEST_VAR_1' => 'some-value',
        'NON_Apartment_TEST_VAR_1' => 'some-other-value',
        'Apartment_TEST_VAR_2' => 213456,
    ];

    /**
     * Pass vars to $_SERVER
     */
    public function setUp()
    {
        foreach (static::$server as $name => $value) {
            $_SERVER[$name] = $value;
        }
    }

    /**
     * Clean-up
     */
    public function tearDown()
    {
        foreach (static::$server as $name => $value) {
             unset($_SERVER[$name]);
        }
    }

    /**
     * @covers \Apartment\Config\Config::replaceTokens
     * @covers \Apartment\Config\Config::recurseArrayForTokens
     */
    public function testReplaceTokens()
    {
        $config = new Config([
            'some-var-1' => 'includes/%%Apartment_TEST_VAR_1%%',
            'some-var-2' => 'includes/%%NON_Apartment_TEST_VAR_1%%',
            'some-var-3' => 'includes/%%Apartment_TEST_VAR_2%%',
            'some-var-4' => 123456,
        ]);

        $this->assertContains(
            static::$server['Apartment_TEST_VAR_1'] . '', // force convert to string
            $config->offsetGet('some-var-1')
        );
        $this->assertNotContains(
            static::$server['NON_Apartment_TEST_VAR_1'] . '', // force convert to string
            $config->offsetGet('some-var-2')
        );
        $this->assertContains(
            static::$server['Apartment_TEST_VAR_2'] . '', // force convert to string
            $config->offsetGet('some-var-3')
        );
    }

    /**
     * @covers \Apartment\Config\Config::replaceTokens
     * @covers \Apartment\Config\Config::recurseArrayForTokens
     */
    public function testReplaceTokensRecursive()
    {
        $config = new Config([
            'folding' => [
                'some-var-1' => 'includes/%%Apartment_TEST_VAR_1%%',
                'some-var-2' => 'includes/%%NON_Apartment_TEST_VAR_1%%',
                'some-var-3' => 'includes/%%Apartment_TEST_VAR_2%%',
                'some-var-4' => 123456,
            ]
        ]);

        $folding = $config->offsetGet('folding');

        $this->assertContains(
            static::$server['Apartment_TEST_VAR_1'] . '', // force convert to string
            $folding['some-var-1']
        );
        $this->assertNotContains(
            static::$server['NON_Apartment_TEST_VAR_1'] . '', // force convert to string
            $folding['some-var-2']
        );
        $this->assertContains(
            static::$server['Apartment_TEST_VAR_2'] . '', // force convert to string
            $folding['some-var-3']
        );
    }
}
