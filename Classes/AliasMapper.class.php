<?php

namespace Realitaetsverlust\Carbuncle;

/**
 * Responsible for finding the correct class for a given alias
 */
class AliasMapper {
    public static function getClassForAlias(string $alias) : string {
        $aliases = json_decode(file_get_contents('aliases.json'));
        if($aliases->$alias === null) {
            StreamWriter::write("The command '{$alias}' does not exist");
            exit();
        }

        return $aliases->$alias;
    }
}