<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Dotenv\Dotenv;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

if (file_exists('.env')) {
    $dotenv = new Dotenv();
    $dotenv->load('.env');
}

/**
 * @var \Psr\Container\ContainerInterface $container
 */
$container = require 'config/container.php';

$cli = new Application('Application console');


$entityManager = $container->get(EntityManagerInterface::class);
$connection = $entityManager->getConnection();

$migrationConnection = DriverManager::getConnection($container['config']['doctrine']['connection']);

$configuration = new PhpFile('bin/migrations.php');



$cli->getHelperSet()->set(new EntityManagerHelper($entityManager), 'em');
$dependencyFactory = DependencyFactory::fromConnection($configuration, new ExistingConnection($connection));

ConsoleRunner::addCommands($cli);
\Doctrine\Migrations\Tools\Console\ConsoleRunner::addCommands($cli);

$commands = $container->get('config')['console']['commands'];

foreach ($commands as $command) {
    $cli->add($container->get($command));
}
$cli->run();