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
        // resetting the keys
        $arguments = array_values($arguments);

        $className = "\\Realitaetsverlust\\Carbuncle\\" . AliasMapper::getClassForAlias($command);
        // TODO: Make repo variable
        $classToCall = new $className("steam");
        $classToCall->exec($arguments);
    }
}
