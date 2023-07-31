<?php
//namespace Ore;
use Ore\Controller\Ctrl;
use Ore\Utils\ConfigLoader;

require_once __DIR__  . "/../../vendor/autoload.php";

$config = new ConfigLoader(dirname(dirname(__DIR__)));
$configBase = $config->load("base.json");

$debug = false;

if ($configBase['php']['debug'])
{
	$debug = true;
}

if ($debug)
{
	$whoops = new \Whoops\Run;
	$whoops ->pushHandler(new \Whoops\Handler\PrettyPageHandler);
	$whoops->register();
}


