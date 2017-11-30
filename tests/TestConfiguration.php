<?php
/*
 * This file is part of the Apartment package.
 *
 * (c) Rob Morgan <robbym@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Use the notation:
 *
 * defined(...) || define(...);
 *
 * This ensures that, when a test is marked to run in a separate process,
 * PHP will not complain of a constant already being defined.
 */

/**
 * Apartment_Db_Adapter_SqlServerAdapter
 */
defined('TESTS_Apartment_DB_ADAPTER_SQLSRV_ENABLED') || define('TESTS_Apartment_DB_ADAPTER_SQLSRV_ENABLED', getenv('TESTS_Apartment_DB_ADAPTER_SQLSRV_ENABLED'));
defined('TESTS_Apartment_DB_ADAPTER_SQLSRV_HOST') || define('TESTS_Apartment_DB_ADAPTER_SQLSRV_HOST', getenv('TESTS_Apartment_DB_ADAPTER_SQLSRV_HOST'));
defined('TESTS_Apartment_DB_ADAPTER_SQLSRV_USERNAME') || define('TESTS_Apartment_DB_ADAPTER_SQLSRV_USERNAME', getenv('TESTS_Apartment_DB_ADAPTER_SQLSRV_USERNAME'));
defined('TESTS_Apartment_DB_ADAPTER_SQLSRV_PASSWORD') || define('TESTS_Apartment_DB_ADAPTER_SQLSRV_PASSWORD', getenv('TESTS_Apartment_DB_ADAPTER_SQLSRV_PASSWORD'));
defined('TESTS_Apartment_DB_ADAPTER_SQLSRV_DATABASE') || define('TESTS_Apartment_DB_ADAPTER_SQLSRV_DATABASE', getenv('TESTS_Apartment_DB_ADAPTER_SQLSRV_DATABASE'));
defined('TESTS_Apartment_DB_ADAPTER_SQLSRV_PORT') || define('TESTS_Apartment_DB_ADAPTER_SQLSRV_PORT', getenv('TESTS_Apartment_DB_ADAPTER_SQLSRV_PORT'));

/**
 * Apartment_Db_Adapter_MysqlAdapter
 */
defined('TESTS_Apartment_DB_ADAPTER_MYSQL_ENABLED') || define('TESTS_Apartment_DB_ADAPTER_MYSQL_ENABLED', getenv('TESTS_Apartment_DB_ADAPTER_MYSQL_ENABLED'));
defined('TESTS_Apartment_DB_ADAPTER_MYSQL_HOST') || define('TESTS_Apartment_DB_ADAPTER_MYSQL_HOST', getenv('TESTS_Apartment_DB_ADAPTER_MYSQL_HOST'));
defined('TESTS_Apartment_DB_ADAPTER_MYSQL_USERNAME') || define('TESTS_Apartment_DB_ADAPTER_MYSQL_USERNAME', getenv('TESTS_Apartment_DB_ADAPTER_MYSQL_USERNAME'));
defined('TESTS_Apartment_DB_ADAPTER_MYSQL_PASSWORD') || define('TESTS_Apartment_DB_ADAPTER_MYSQL_PASSWORD', getenv('TESTS_Apartment_DB_ADAPTER_MYSQL_PASSWORD'));
defined('TESTS_Apartment_DB_ADAPTER_MYSQL_DATABASE') || define('TESTS_Apartment_DB_ADAPTER_MYSQL_DATABASE', getenv('TESTS_Apartment_DB_ADAPTER_MYSQL_DATABASE'));
defined('TESTS_Apartment_DB_ADAPTER_MYSQL_PORT') || define('TESTS_Apartment_DB_ADAPTER_MYSQL_PORT', getenv('TESTS_Apartment_DB_ADAPTER_MYSQL_PORT'));
defined('TESTS_Apartment_DB_ADAPTER_MYSQL_UNIX_SOCKET') || define('TESTS_Apartment_DB_ADAPTER_MYSQL_UNIX_SOCKET', getenv('TESTS_Apartment_DB_ADAPTER_MYSQL_UNIX_SOCKET'));

/**
 * Apartment_Db_Adapter_PostgresAdapter
 */
defined('TESTS_Apartment_DB_ADAPTER_POSTGRES_ENABLED') || define('TESTS_Apartment_DB_ADAPTER_POSTGRES_ENABLED', getenv('TESTS_Apartment_DB_ADAPTER_POSTGRES_ENABLED'));
defined('TESTS_Apartment_DB_ADAPTER_POSTGRES_HOST') || define('TESTS_Apartment_DB_ADAPTER_POSTGRES_HOST', getenv('TESTS_Apartment_DB_ADAPTER_POSTGRES_HOST'));
defined('TESTS_Apartment_DB_ADAPTER_POSTGRES_USERNAME') || define('TESTS_Apartment_DB_ADAPTER_POSTGRES_USERNAME', getenv('TESTS_Apartment_DB_ADAPTER_POSTGRES_USERNAME'));
defined('TESTS_Apartment_DB_ADAPTER_POSTGRES_PASSWORD') || define('TESTS_Apartment_DB_ADAPTER_POSTGRES_PASSWORD', getenv('TESTS_Apartment_DB_ADAPTER_POSTGRES_PASSWORD'));
defined('TESTS_Apartment_DB_ADAPTER_POSTGRES_DATABASE') || define('TESTS_Apartment_DB_ADAPTER_POSTGRES_DATABASE', getenv('TESTS_Apartment_DB_ADAPTER_POSTGRES_DATABASE'));
defined('TESTS_Apartment_DB_ADAPTER_POSTGRES_PORT') || define('TESTS_Apartment_DB_ADAPTER_POSTGRES_PORT', getenv('TESTS_Apartment_DB_ADAPTER_POSTGRES_PORT'));
defined('TESTS_Apartment_DB_ADAPTER_POSTGRES_DATABASE_SCHEMA') || define('TESTS_Apartment_DB_ADAPTER_POSTGRES_DATABASE_SCHEMA', getenv('TESTS_Apartment_DB_ADAPTER_POSTGRES_DATABASE_SCHEMA'));

/**
 * Apartment_Db_Adapter_SQLiteAdapter
 */
defined('TESTS_Apartment_DB_ADAPTER_SQLITE_ENABLED') || define('TESTS_Apartment_DB_ADAPTER_SQLITE_ENABLED', getenv('TESTS_Apartment_DB_ADAPTER_SQLITE_ENABLED'));
defined('TESTS_Apartment_DB_ADAPTER_SQLITE_DATABASE') || define('TESTS_Apartment_DB_ADAPTER_SQLITE_DATABASE', getenv('TESTS_Apartment_DB_ADAPTER_SQLITE_DATABASE'));
