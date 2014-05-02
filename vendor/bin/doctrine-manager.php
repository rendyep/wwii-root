<?php

class DoctrineCLI
{
    protected $entityManagers = array();

    protected $activeEntityManager = null;

    protected $commands = array();

    protected $specialCommands = array();

    public function __construct()
    {
        $this->entityManagers[0]['key']    = '1';
        $this->entityManagers[0]['name']   = 'MySQL Entity Manager';
        $this->entityManagers[0]['active'] = true;

        $this->entityManagers[1]['key']  = '2';
        $this->entityManagers[1]['name'] = 'MsSQL Entity Manager';
        $this->entityManagers[1]['active'] = false;

        $this->commands[0]['key']  = '1';
        $this->commands[0]['name'] = 'Validate schema';

        $this->commands[1]['key']  = '2';
        $this->commands[1]['name'] = 'Dump Update Schema SQL';

        $this->commands[2]['key']  = '3';
        $this->commands[2]['name'] = 'Execute Update Schema SQL';

        $this->specialCommands[0]['key'] = 'b';
        $this->specialCommands[0]['name'] = 'Back';

        $this->specialCommands[1]['key'] = 'x';
        $this->specialCommands[1]['name'] = 'Exit';
    }

    public function run()
    {
        $this->displayMessages($this->getWelcomeMessages());

        while (true) {
            if ($this->activeEntityManager == null) {
                $message = $this->getPreSelectMessages()
                    . $this->getSelectMessages()
                    . $this->getSpecialCommandMessages(false)
                    . $this->getInputMessages();

                $input = $this->getInput($message);

                $this->translateInput($input);
            } else {
                $message = $this->getPreCommandMessages()
                    . $this->getCommandMessages()
                    . $this->getSpecialCommandMessages(true)
                    . $this->getInputMessages();

                $input = $this->getInput($message);

                $this->translateInput($input);
            }
        }
    }

    protected function getWelcomeMessages()
    {
        $message = PHP_EOL . 'Welcome to Doctrine Custom CLI.' . PHP_EOL . PHP_EOL;

        return $message;
    }

    protected function getPreSelectMessages()
    {
        $message = 'Select an Entity Manager:' . PHP_EOL;

        return $message;
    }

    protected function getSelectMessages()
    {
        $message = '';

        foreach ($this->entityManagers as $entityManager) {
            $message .= $entityManager['key'] . '. ' . $entityManager['name'] . PHP_EOL;
        }

        return $message;
    }

    protected function getPreCommandMessages()
    {
        $message = 'Active Entity Manager: ' . $this->activeEntityManager['name'] . PHP_EOL;

        return $message;
    }

    protected function getCommandMessages()
    {
        $message = '';

        foreach ($this->commands as $command) {
            $message .= $command['key'] . '. ' . $command['name'] . PHP_EOL;
        }

        return $message;
    }

    protected function getSpecialCommandMessages($includeBack = true)
    {
        $message = '';

        foreach ($this->specialCommands as $command) {
            if ($includeBack || (!$includeBack && $command['key'] != 'b')) {
                $message .= $command['key'] . '. ' . $command['name'] . PHP_EOL;
            }
        }

        return $message;
    }

    protected function getInputMessages()
    {
        $message = PHP_EOL . 'Choice : ';

        return $message;
    }

    protected function getMaintenanceMessages($target)
    {
        $message = $target['name'] . ' is under maintenance.' . PHP_EOL . PHP_EOL;

        return $message;
    }

    protected function getErrorMessages()
    {
        $message = 'Wrong choice...' . PHP_EOL . PHP_EOL;

        return $message;
    }

    protected function displayMessages($messages)
    {
        fwrite(STDOUT, $messages);
    }

    protected function getInput($message)
    {
        $this->displayMessages($message);
        $input = chop(fgets(STDIN));
        fwrite(STDOUT, PHP_EOL);

        return $input;
    }

    protected function translateInput($input)
    {
        if (empty($this->activeEntityManager)) {
            switch ($input) {
                case '1':
                case '2':
                    $activeEntityManager = null;
                    foreach ($this->entityManagers as $entityManager) {
                        if ($entityManager['key'] == $input) {
                            if ($entityManager['active']) {
                                $activeEntityManager = $entityManager;
                            } else {
                                $this->displayMessages($this->getMaintenanceMessages($entityManager));
                                return;
                            }
                        }
                    }

                    if ($activeEntityManager == null) {
                        $this->displayMessages($this->getErrorMessages());
                    } else {
                        $this->activeEntityManager = $activeEntityManager;
                    }
                    break;
                case 'x':
                    $this->terminate();
                    break;
                default:
                    $this->displayMessages($this->getErrorMessages());
                    break;
            }
        } else {
            chdir(dirname(__FILE__));

            switch ($input) {
                case '1':
                    $this->displayMessages('Please wait...' . PHP_EOL);
                    $this->displayMessages('==========================================================' . PHP_EOL . PHP_EOL);
                    system('doctrine.php.bat orm:validate-schema');
                    $this->displayMessages(PHP_EOL . '==========================================================' . PHP_EOL . PHP_EOL);
                    break;
                case '2':
                    $this->displayMessages('Please wait...' . PHP_EOL);
                    $this->displayMessages('==========================================================' . PHP_EOL);
                    system('doctrine.php.bat orm:schema-tool:update --dump-sql');
                    $this->displayMessages('==========================================================' . PHP_EOL . PHP_EOL);
                    break;
                case '3':
                    $this->displayMessages('Please wait...' . PHP_EOL);
                    $this->displayMessages('==========================================================' . PHP_EOL);
                    system('doctrine.php.bat orm:schema-tool:update --force');
                    $this->displayMessages('==========================================================' . PHP_EOL . PHP_EOL);
                    break;
                case 'b':
                    $this->activeEntityManager = null;
                    break;
                case 'x':
                    $this->terminate();
                    break;
            }
        }
    }

    protected function terminate($code = 0)
    {
        fwrite(STDOUT, 'Teriminating...' . PHP_EOL);
        $this->activeEntityManager = null;
        sleep(1);
        exit($code);
    }
}

$cli = new DoctrineCLI();
$cli->run();
