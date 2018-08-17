<?php

require_once __DIR__ . '/../vendor/autoload.php';

defined('TESTS_BLADE_DB_POSTGRES_ENABLED') || define('TESTS_BLADE_DB_POSTGRES_ENABLED', getenv('TESTS_BLADE_DB_POSTGRES_ENABLED'));
defined('TESTS_BLADE_DB_POSTGRES_HOST') || define('TESTS_BLADE_DB_POSTGRES_HOST', getenv('TESTS_BLADE_DB_POSTGRES_HOST'));
defined('TESTS_BLADE_DB_POSTGRES_USERNAME') || define('TESTS_BLADE_DB_POSTGRES_USERNAME', getenv('TESTS_BLADE_DB_POSTGRES_USERNAME'));
defined('TESTS_BLADE_DB_POSTGRES_PASSWORD') || define('TESTS_BLADE_DB_POSTGRES_PASSWORD', getenv('TESTS_BLADE_DB_POSTGRES_PASSWORD'));
defined('TESTS_BLADE_DB_POSTGRES_DATABASE') || define('TESTS_BLADE_DB_POSTGRES_DATABASE', getenv('TESTS_BLADE_DB_POSTGRES_DATABASE'));
defined('TESTS_BLADE_DB_POSTGRES_PORT') || define('TESTS_BLADE_DB_POSTGRES_PORT', getenv('TESTS_BLADE_DB_POSTGRES_PORT'));

defined('TESTS_BLADE_DB_MYSQL_ENABLED')  || define('TESTS_BLADE_DB_MYSQL_ENABLED',  getenv('TESTS_BLADE_DB_MYSQL_ENABLED'));
defined('TESTS_BLADE_DB_MYSQL_HOST')     || define('TESTS_BLADE_DB_MYSQL_HOST',     getenv('TESTS_BLADE_DB_MYSQL_HOST'));
defined('TESTS_BLADE_DB_MYSQL_USERNAME') || define('TESTS_BLADE_DB_MYSQL_USERNAME', getenv('TESTS_BLADE_DB_MYSQL_USERNAME'));
defined('TESTS_BLADE_DB_MYSQL_PASSWORD') || define('TESTS_BLADE_DB_MYSQL_PASSWORD', getenv('TESTS_BLADE_DB_MYSQL_PASSWORD'));
defined('TESTS_BLADE_DB_MYSQL_DATABASE') || define('TESTS_BLADE_DB_MYSQL_DATABASE', getenv('TESTS_BLADE_DB_MYSQL_DATABASE'));
defined('TESTS_BLADE_DB_MYSQL_PORT')     || define('TESTS_BLADE_DB_MYSQL_PORT',     getenv('TESTS_BLADE_DB_MYSQL_PORT'));
