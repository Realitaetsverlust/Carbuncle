<?php

namespace Realitaetsverlust\Carbuncle;

/**
 * Responsible for finding the correct class for a given alias
 */
class AliasMapper {
    // TODO: We don't really need this anymore. Remove this class and tne aliases.json and move everything to a switch case in Carbuncle.php
    public static function getClassForAlias(string $alias) : string {
        $aliases = json_decode(file_get_contents('aliases.json'));
        if($aliases->$alias === null) {
            StreamWriter::write("The command '{$alias}' does not exist");
            exit();
        }

        return $aliases->$alias;
    }
}