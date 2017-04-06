<?php
require_once 'vendor/symfony/class-loader/Psr4ClassLoader.php';
require_once 'vendor/autoload.php';
use Symfony\Component\ClassLoader\Psr4ClassLoader;
$loader = new Psr4ClassLoader();
$loader->addPrefix('TriviaGame', dirname(__FILE__).'/src');
$loader->register();