<?php

require_once 'vendor/autoload.php';

$dir = dirname(__FILE__).'/db';

return array(
    'paths' => array(
        'migrations' => $dir.'/migrations',
        'seeds' => $dir.'/seeds'
    ),
    'environments' => array(
        'default_database' => 'development',
        'default_tenants_table' => 'tenants',
        'development' => array(
            'name' => 'devdb',
            'connection' => new \PDO('sqlite3:development.sqlite3'),
            'tenants' => array(
                new \PDO('sqlite3:tenant1.sqlite3'),
                new \PDO('sqlite3:tenant2.sqlite3'),
                new \PDO('sqlite3:tenant3.sqlite3')
            )
        ),
        'testing' => array(
            'name' => 'testdb',
            'connection' => new \PDO('sqlite3:host=localhost;dbname=testing', 'user', 'pass'),
            'tenants' => array(
                new \PDO('sqlite3:test_tenant1.sqlite3'),
                new \PDO('sqlite3:test_tenant2.sqlite3'),
                new \PDO('sqlite3:test_tenant3.sqlite3')
            )
        ),
        'production' => array(
            'name' => 'proddb',
            'connection' => new \PDO('mysql:host='.getenv("DB_HOST").';dbname='.getenv("DB_NAME"), getenv("DB_USER"), getenv("DB_PASS")),
            'tenants' => function($landlord) {
                $result = $landlord->query('SELECT * FROM tenants');
                while ($record = $result->fetch(PDO::FETCH_ASSOC)) {
                    yield new PDO("mysql:host={$record['hostname']};dbname={$record['dbname']}", $record['username'], $tenant['password']);
                }
            }
        )
    ),
);
