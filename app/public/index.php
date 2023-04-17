<?php

use App\Http\Router\RoutesConfiguration;
use App\ServiceContainer\AppServiceProvider;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$container = (new AppServiceProvider())->registerAndGetContainer();

/** @var RoutesConfiguration $routesConfiguration */
$routesConfiguration = $container[RoutesConfiguration::class];
$routesConfiguration->run();
