<?php
use Ore\Router;
use Ore\Controller\Ctrl;
use Ore\Utils\ConfigLoader;
use Ore\Reflection;

require_once __DIR__  . "/../../vendor/autoload.php";

$config = new ConfigLoader(dirname(dirname(__DIR__)));
$configBase = $config->load("base.json");
$appDir = dirname(__DIR__) . "/app/";

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

// config app base
$routeConfig = "";
if ($configBase['App'])
{
	if ($configBase['App']['route'])
	{
		$routeConfig = $configBase['App']['route_conf'];
	}
}

if ($routeConfig)
{
	$router->setRouterConfig($routeConfig);
}

if (!empty($_SERVER["PATH_INFO"]))
{
	$routeData = $router->setPath($_SERVER["PATH_INFO"]);
	$ctrl = $routeData["Ctrl"] . "Ctrl";
	$viewDirName = $routeData["Ctrl"];
	$ctrlPlusPath = !empty($routeData["AppDir"]) ? $routeData["AppDir"] . "/" : "/";
	$ctrlPath = $appDir.$ctrlPlusPath."Controller/".$ctrl.".php";
	if (!file_exists($ctrlPath)) {
		if ($debug) 
		{
			throw new Exception("Controller Not Found");
		}
		else
		{
			//ToDo
			//404Ctrl
		}
	}
	// ToDo
	// I'd like to be able to change namespace later.
	$ctrl = "\\OApp\\Controller\\" . $ctrl;
	$ctrlObject = new $ctrl();
	$viewPath = dirname(__DIR__) . "/app/View/{$viewDirName}/";	
}
else
{
	$ctrl = "\\Ore\\Controller\\DefaultCtrl";
	$ctrlObject = new $ctrl();
	$viewPath = dirname(__DIR__) . "/app/View/Default/";
}
$ctrlObject->setViewPath($viewPath);
if (empty($routeData["Method"])) {
	$method = "Index";
}
else
{
	$method = $routeData["Method"];
}
try {
	$ctrlObject->setViewFileName(strtolower($method) . ".tpl");
}
catch(Exception $e) {
	throw $e;
}

$ref = new Reflection($ctrlObject);
$methodParams = $ref->getMethod($method)->getParameters();
if ($methodParams)
{
	foreach($methodParams as $k => $param)
	{
		$paramSetting[$k]['name'] = $param->getName();
		$getType = $param->getType();
		$paramSetting[$k]['type'] = $getType instanceof ReflectionNamedType ? $getType->getName() : "";
		if (empty($routeData["Args"]))
		{
			throw new Exception("Method Arg Error ");
		}
		else if (empty($routeData["Args"][$paramSetting[$k]["name"]]))
		{
			throw new Exception("Method Arg Error ");
		}
	}
	$ctrlObject->$method(...$routeData["Args"]);
}
else 
{
	$ctrlObject->$method();
}

