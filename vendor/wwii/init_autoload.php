<?php

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use WWII\Config\ConfigManager;
use WWII\Service\ServiceManager;
use WWII\Service\ServiceManagerInterface;

if (!isset($loader)) {
    $loader = include(__DIR__ . "/../autoload.php");
}

$loader->set("WWII\\Domain", __DIR__ . '/domain/src/');
$loader->register(true);
$loader->set("WWII\\Application", __DIR__ . '/application/src/');
$loader->register(true);
$loader->set("WWII\\Console", __DIR__ . '/console/src/');
$loader->register(true);
$loader->set("WWII\Common", __DIR__ . '/common/src');
$loader->register(true);
$loader->set("WWII\\", __DIR__ . '/core/src/');
$loader->register(true);

$isDevMode = true;
$configManager = ConfigManager::getInstance();
$config = Setup::createXMLMetadataConfiguration($configManager->get('doctrine_mappings'), $isDevMode);
$connection = $configManager->get('database');

$entityManager = EntityManager::create($connection, $config);
$platform = $entityManager->getConnection()->getDatabasePlatform();
$platform->registerDoctrineTypeMapping('enum', 'string');

$serviceManager = new ServiceManager();
$serviceManager->setConfigManager($configManager);
