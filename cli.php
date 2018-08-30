<?php
/**
 * Created by PhpStorm.
 * User: tong
 * Date: 2018/8/30
 * Time: 10:24
 */
use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Loader;

mb_internal_encoding('UTF-8');
ini_set('default_charset', 'UTF-8');
date_default_timezone_set('Asia/shanghai');
//
define('APP_PATH', realpath('.'));
define('APP_ENV', getenv('ENV'));
define('APP_DEBUG', (bool)get_cfg_var('app.debug'));

// Using the CLI factory default services container
$di = new CliDI();

// Load the configuration file (if any)
$config = include APP_PATH . '/config/' . APP_ENV . '/config.main.php';

/**
 * Include Services
 */
include APP_PATH . '/services.php';

/**
 * Include Autoloader
 */
include APP_PATH . '/loader.php';

// Create a console application
$console = new ConsoleApp();

$console->setDI($di);

/**
 * Process the console arguments
 */
$arguments = [];

foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments['task'] = $arg;
    } elseif ($k === 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

try {
    // Handle incoming arguments
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    // Do Phalcon related stuff here
    // ..
    fwrite(STDERR, $e->getMessage() . PHP_EOL);
    exit(1);
} catch (\Throwable $throwable) {
    fwrite(STDERR, $throwable->getMessage() . PHP_EOL);
    exit(1);
}