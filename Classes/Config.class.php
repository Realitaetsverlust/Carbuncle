<?php

namespace Realitaetsverlust\Carbuncle;

use stdClass;

/**
 * Config parser
 */
class Config {
    private static string $configLocation;
    private static string $configName = 'carbuncle.json';
    private static string $configPath;
    private static stdClass $config;

    public static function init() {
        self::$configLocation = $_SERVER['HOME'] . '/.config/realitaetsverlust/carbuncle/';
        self::$configPath = self::$configLocation . self::$configName;
        self::isConfigEditable();
        self::parseConfig();
    }

    // TODO: Convert exit's to StreamWriter::writeError()
    private static function isConfigEditable() {
        @mkdir($_SERVER['HOME'] . '/.config/realitaetsverlust/carbuncle', 0755, true);

        if(!file_exists(self::$configPath)) {
            if(file_put_contents(self::$configPath, file_get_contents('defaultConfig.json')) === false) {
                exit('The config file under ' . self::$configPath . ' could not be created! Please ensure the permissions are set correctly!');
            }
        }

        if(!is_readable(self::$configPath)) {
            exit('The config file under ' . self::$configPath . ' is not readable! Please ensure the permissions are set correctly!');
        }

        if(!is_writable(self::$configPath)) {
            exit('The config file under ' . self::$configPath . ' is not writeable! Please ensure the permissions are set correctly!');
        }
    }

    private static function parseConfig() {
        self::$config = json_decode(file_get_contents($_SERVER['HOME'] . '/.config/realitaetsverlust/carbuncle/carbuncle.json'));
    }

    public static function fetchRepositories() {
        return self::$config->protonRepos;
    }

    public static function fetchDateFormat() {
        return self::$config->dateFormat;
    }
}