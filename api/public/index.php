<?php

declare(strict_types=1);

use Api\Http\Action;
use Slim\App;
use Slim\Container;
use Symfony\Component\Dotenv\Dotenv;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

if (file_exists('.env')) {
    (new Dotenv())->load('.env');
}

(function () {
    $container = require 'config/container.php';
    $app = new App($container);

    (require 'config/routes.php')($app);

    $app->run();
})();

