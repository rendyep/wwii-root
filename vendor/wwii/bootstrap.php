<?php

include(__DIR__ . '/init_autoload.php');

if (php_sapi_name() === 'cli') {
    return false;
}

$application = new \WWII\Application();
$application->setEntityManager($entityManager);
$application->run();
