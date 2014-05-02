<?php

require_once(__DIR__ . '/../wwii/bootstrap.php');

$config = include(__DIR__ . '/../wwii/console/config/config.default.php');

$shortOptions = 'a:'
              . 's::'
              . 'e::';

$requestedOptions = getopt($shortOptions);

if (empty($requestedOptions['a'])) {
    return fwrite(STDOUT, PHP_EOL . 'Option -a must be specified.' . PHP_EOL);
} else {
    if (!isset($config['app'][$requestedOptions['a']])) {
        return fwrite(STDOUT, PHP_EOL . 'Application "' . $requestedOptions['a'] . '" is not recognized.' . PHP_EOL);
    }
}

$configManager = \WWII\Config\ConfigManager::getInstance();
$serviceManager = new \WWII\Service\ServiceManager();
$serviceManager->setConfigManager($configManager);

$application = $config['app'][$requestedOptions['a']];
$system = new $application($serviceManager, $entityManager);

if (isset($requestedOptions['s']) && !empty($requestedOptions['s'])) {
    if (method_exists($application, 'setDateStart')) {
        try {
            $dateStart = new \DateTime($options['s']);
            $application->setDateStart();
        } catch (\Exception $e) {
            fwrite(STDOUT, 'Option "s" has invalid value (ex: 2014-04-21)');
        }
    } else {
        fwrite(STDOUT, 'Application "' . $requestedOptions['a'] . '" doesn\'t accept option "s"');
    }
}

if (isset($requestedOptions['e']) && !empty($requestedOptions['e'])) {
    if (method_exists($application, 'setDateEnd')) {
        try {
            $dateStart = new \DateTime($options['e']);
            $application->setDateStart();
        } catch (\Exception $e) {
            fwrite(STDOUT, 'Option "e" has invalid value (ex: 2014-04-21)');
        }
    } else {
        fwrite(STDOUT, 'Application "' . $requestedOptions['a'] . '" doesn\'t accept option "e"');
    }
}

$system->run();
