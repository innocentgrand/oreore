<?php
use Ore\Router;
use Ore\Controller\Ctrl;
use Ore\Utils\ConfigLoader;

require_once __DIR__  . "/../../vendor/autoload.php";

$config = new ConfigLoader(dirname(dirname(__DIR__)));
$configBase = $config->load("base.json");

$debug = false;

if ($configBase['php']['debug'])
{
	$debug = true;
	error_reporting(E_ALL);
	ini_set('display_errors', "On");
}

if ($debug)
{
	$whoops = new \Whoops\Run;
	$whoops ->pushHandler(new \Whoops\Handler\PrettyPageHandler);
	$whoops->register();
}

if ($debug) 
{
	$c = new Ctrl();
	//$c->vt($_SERVER);
}

$router = new Router();
if (!empty($_SERVER["PATH_INFO"]))
{
	$routeData = $router->setPath($_SERVER["PATH_INFO"]);
	
}
