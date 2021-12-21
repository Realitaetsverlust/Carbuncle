<?php

namespace Realitaetsverlust\Carbuncle;

use CommandInterface;
use PharData;

/**
 * Remove command. Removed a given version from file system.
 */
class Remove extends BaseCommand implements CommandInterface {
    public function exec(array $arguments = []) {
        $id = readline("Which version would you like to install (ID via 'carbuncle list-versions'): ");
        $repo = new ProtonRepo($this->activeRepo);
        if (!$repo->removeVersionFromFilesystem($id)) {
            StreamWriter::write('Removal of given version failed');
            exit();
        }

        StreamWriter::write('Version successfully removed!');
    }
}