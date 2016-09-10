<?php
require __DIR__.'/../vendor/autoload.php';

use App\Application\Application;

$app = new Application(realpath(__DIR__.'/..'));

require $app->basePath().'/src/routes.php';

$app->dispatch();