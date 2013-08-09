<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

// Use APC for autoloading to improve performance
$loader = new ApcClassLoader('Kunstmaan', $loader);
$loader->register(true);

require_once __DIR__.'/../app/AppKernel.php';

if (stripos(gethostname(), 'productionhostnamefixme.com') !== false){
    $kernel = new AppKernel('prod', false);
} else {
    $kernel = new AppKernel('staging', false);
}
$kernel->loadClassCache();
if (!isset($_SERVER['HTTP_SURROGATE_CAPABILITY']) || false === strpos($_SERVER['HTTP_SURROGATE_CAPABILITY'], 'ESI/1.0')) {
    require_once __DIR__.'/../app/AppCache.php';
    $kernel = new AppCache($kernel);
}
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
