<?php

namespace Realitaetsverlust\Carbuncle;

/**
 * Stream writer used to write to stdout
 */
class StreamWriter {
    // TODO: Implement methods
    public static function writeInfo() {

    }

    public static function writeSuccess() {

    }

    public static function writeWarning() {

    }

    public static function writeError() {

    }

    public static function write(string $message) {
        fwrite(fopen('php://stdout', 'w'), $message . PHP_EOL);
    }
}
