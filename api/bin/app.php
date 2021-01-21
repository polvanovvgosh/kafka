<?php

declare(strict_types=1);

use Doctrine\DBAL\DriverManager;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\ExistingConfiguration;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Metadata\Storage\TableMetadataStorageConfiguration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Symfony\Component\Console\Application;
use Doctrine\Migrations\Tools\Console\Command;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * @var \Psr\Container\ContainerInterface $container
 */
$container = require 'config/container.php';


$entityManager = $container->get(EntityManagerInterface::class);
$connection    = DriverManager::getConnection($container->get('config')['doctrine']['connection']);

$configuration = new Configuration($connection);

$configuration->addMigrationsDirectory('App\Data\Migrations', './src/Data/Migrations');
$configuration->setAllOrNothing(true);
$configuration->setCheckDatabasePlatform(false);

$storageConfiguration = new TableMetadataStorageConfiguration();
$storageConfiguration->setTableName('doctrine_migration_versions');

$configuration->setMetadataStorageConfiguration($storageConfiguration);

$dependencyFactory = DependencyFactory::fromEntityManager(
    new ExistingConfiguration($configuration),
    new ExistingEntityManager($entityManager)
);

$cli = new Application('Application console');

$cli->getHelperSet()->set(new EntityManagerHelper($entityManager), 'em');
$cli->setCatchExceptions(true);

ConsoleRunner::addCommands($cli);

$commands = $container->get('config')['console']['commands'];

$cli->addCommands(
    [
        new Command\DumpSchemaCommand($dependencyFactory),
        new Command\ExecuteCommand($dependencyFactory),
        new Command\GenerateCommand($dependencyFactory),
        new Command\LatestCommand($dependencyFactory),
        new Command\ListCommand($dependencyFactory),
        new Command\MigrateCommand($dependencyFactory),
        new Command\RollupCommand($dependencyFactory),
        new Command\StatusCommand($dependencyFactory),
        new Command\SyncMetadataCommand($dependencyFactory),
        new Command\VersionCommand($dependencyFactory),
        new Command\DiffCommand($dependencyFactory),
    ]
);

foreach ($commands as $command) {
    $cli->add($container->get($command));
}

$cli->run();