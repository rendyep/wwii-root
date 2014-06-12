<?php
// Include the Console_CommandLine package.
require_once 'Console/CommandLine.php';
require_once __DIR__ . '/../wwii/bootstrap.php';

$config = include __DIR__ . '/../wwii/console/config/config.default.php';

$parser = new Console_CommandLine(array(
    'description' => 'WWII Console Application v-1.0.0',
    'version'     => '1.0.0'
));

$parser->addOption('verbose', array(
    'short_name'  => '-v',
    'long_name'   => '--verbose',
    'action'      => 'StoreTrue',
    'description' => 'turn on verbose output'
));

foreach ($config['console']['commands'] as $strCommand => $arrayCommand) {
    $command = $parser->addCommand($strCommand, array(
        'description' => $arrayCommand['description']
    ));

    if (isset($arrayCommand['options']) && ! empty($arrayCommand['options'])) {
        foreach ($arrayCommand['options'] as $strOption => $arrayOption) {
            $command->addOption($strOption, $arrayOption);
        }
    }

    if (isset($arrayCommand['arguments']) && ! empty($arrayCommand['arguments'])) {
        foreach ($arrayCommand['arguments'] as $strArgument => $arrayArgument) {
            $command->addArgument($strArgument, $arrayArgument);
        }
    }
}

try {
    $result = $parser->parse();

    $controllerName = $result->command_name;
    $actionName = 'defaultAction';

    if (! empty($result->command)) {
        if (isset($result->command->options)) {
            foreach ($result->command->options as $key => $option) {
                if ($option == 1) {
                    $actionName = $key . 'Action';
                }
            }
        }

        $controller = new $config['console']['commands'][$controllerName]['controller'](
            $serviceManager,
            $entityManager
        );

        $args = null;
        if (isset($result->command->args)) {
            $args = $result->command->args;
        }

        $controller->{$actionName}($args);
    }
} catch (\Exception $e) {
    $parser->displayError($e->getMessage());
}
