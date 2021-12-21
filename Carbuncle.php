<?php

namespace Realitaetsverlust\Carbuncle;

/**
 * Main class. Handles command parsing and init of statics
 */
class Carbuncle {
    public function __construct(array $argv) {
        Config::init();
        Api::init();
        $this->parseCommand($argv);
    }

    public function parseCommand(array $argv) {
        $command = $argv[1];

        $arguments = $argv;
        unset($arguments[0], $arguments[1]);

        // TODO: Display help if no argument is given

        // resetting the keys
        $arguments = array_values($arguments);

        $className = "\\Realitaetsverlust\\Carbuncle\\" . AliasMapper::getClassForAlias($command);
        $classToCall = new $className(Config::getCurrentRepo());
        $classToCall->exec($arguments);
    }
}
