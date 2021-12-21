<?php

namespace Realitaetsverlust\Carbuncle;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Simple class representation of a proton version
 */
class Proton {
    public function __construct(
        public int $id,
        public string $filename,
        public string $dateModified,
        public string $filepath
    ) {}

    public function getFormattedTime() : string {
        return date(Config::fetchDateFormat(), $this->dateModified);
    }

    public function removeFromFilesystem() : bool {
        $directoryIterator = new RecursiveDirectoryIterator($this->filepath, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($directoryIterator,RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if(is_dir($file)) {
                rmdir($file);
            } else {
                unlink($file);
            }
        }

        return rmdir($this->filepath);
    }
}