<?php

namespace Realitaetsverlust\Carbuncle;

use CommandInterface;

/**
 * Remove command. Removed a given version from file system.
 */
class CacheManager extends BaseCommand implements CommandInterface {
    public function exec(array $arguments = []) {
        switch($arguments[0]) {
            case 'clear':
                Api::invalidateCache();
                StreamWriter::write("Cache cleared successfully!");
                break;
            case 'age':
                break;
            default:
                StreamWriter::write("The command \"{$arguments[0]}\" does not exist!");
                break;
        }
    }
}