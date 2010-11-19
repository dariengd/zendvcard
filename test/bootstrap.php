<?php

/*
 * Start output buffering
 */
ob_start();

/*
 * Set error reporting to the level to which code must comply.
 */
error_reporting(E_ALL | E_STRICT);

/*
 * Set default timezone
 */
date_default_timezone_set('GMT');

/*
 * Testing environment
 */
define('APPLICATION_ENV', 'testing');

/*
 * Determine the root, library, tests, and models directories
 */
$root = realpath(dirname(__FILE__) . '/../');
$library = $root . '/library';
$tests = $root . '/tests';
$models = $root . '/application/models';
$controllers = $root . '/application/controllers';

/*
 * Prepend the library/, tests/, and models/ directories to the
 * include_path. This allows the tests to run out of the box.
 */
$path = array(
		$models,
		$library,
		$tests,
		get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $path));

/**
 * Register autoloader
 */
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace("App_");

/**
 * Store application root in registry
 */
Zend_Registry::set('testRoot', $root);
Zend_Registry::set('testBootstrap', $root . '/application/bootstrap.php');

/*
 * Unset global variables that are no longer needed.
 */
unset($root, $library, $models, $controllers, $tests, $path);