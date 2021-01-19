<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;
use Slim\Container;

$aggregator = new ConfigAggregator([
    new PhpFileProvider(__DIR__.'/common/*.php'),
    new PhpFileProvider(__DIR__.'/' . (getenv('API_ENV') ?: 'prod') . '/*.php'),
]);

$config = $aggregator->getMergedConfig();


return new Container($config);
