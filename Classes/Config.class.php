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

    public static function init() : void {
        self::$configLocation = $_SERVER['HOME'] . '/.config/realitaetsverlust/carbuncle/';
        self::$configPath = self::$configLocation . self::$configName;
        self::isConfigEditable();
        self::parseConfig();
    }

    public static function fetchRepositories() : stdClass {
        return self::$config->protonRepos;
    }

    public static function fetchDateFormat() : string {
        return self::$config->dateFormat;
    }

    public static function getCurrentRepo() : string {
        return self::$config->currentRepo;
    }

    public static function addRepo(string $repoName, string $repoPath) : void {
        self::$config->protonRepos->$repoName = $repoPath;
        self::saveAndReloadConfig();
    }

    public static function removeRepo(string $repoName) : void {
        unset(self::$config->protonRepos->$repoName);
        self::saveAndReloadConfig();
    }

    public static function setCurrentRepo(string $repoName) {
        self::$config->currentRepo = $repoName;
        self::saveAndReloadConfig();
    }

    private static function saveAndReloadConfig() {
        $json = json_encode(self::$config);
        self::writeConfig($json);
        self::parseConfig();
    }

    // TODO: Convert exit's to StreamWriter::writeError()
    // TODO: This should probably be a bool method
    private static function isConfigEditable() : void {
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

    private static function parseConfig() : bool {
        if($result = json_decode(file_get_contents($_SERVER['HOME'] . '/.config/realitaetsverlust/carbuncle/carbuncle.json'))) {
            self::$config = $result;
            return true;
        }

        return false;
    }

    private static function writeConfig(string $content) {
        if(file_put_contents(self::$configPath, $content) === false) {
            exit('The config file under ' . self::$configPath . ' could not be saved! Please ensure the permissions are set correctly!');
        }
    }
}