<?php

namespace Realitaetsverlust\Carbuncle;

use stdClass;


/**
 * Provides data returned from the github API
 */
class Api {
    private static string $apiUrl = 'https://api.github.com/repos/GloriousEggroll/proton-ge-custom/releases';
    private static string $downloadTarget = 'https://github.com/GloriousEggroll/proton-ge-custom/releases/download/';
    private static string $cachePath = "";

    public static function init() {
        self::$cachePath = $_SERVER['HOME'] . '/.config/realitaetsverlust/carbuncle/cache.json';
    }

    public static function fetchLatestVersion() : stdClass {
        return self::fetchData()[0];
    }

    public static function fetchVersionById(string $id) : stdClass|false {
        $version = self::fetchData()[$id];

        if($version !== null) {
            return $version;
        }
        return false;
    }

    public static function fetchAllVersions() : array {
        return self::fetchData();
    }

    public static function fetchData() : array {
        if(self::isCacheValid()) {
            $data = json_decode(self::fetchDataFromCache(), false);
        } else {
            $data = json_decode(self::fetchDataFromApi(), false);
        }

        $apiData = [];

        foreach($data as $d) {
            $element = new stdClass();
            // Fetch the name from the url. Title from the page is not realiable.
            preg_match("/([^\/]+$)/", $d->html_url, $name);

            // Publish date
            $publishDate = new \DateTime($d->published_at);

            // Assigning to object
            $element->name = 'Proton-' . $name[0];
            $element->htmlUrl = $d->html_url;
            $element->tarballUrl = $d->tarball_url;
            $element->downloadUrl = self::$downloadTarget . $name[0] . '/' . $element->name . '.tar.gz';
            $element->publishedAt = $publishDate->format(Config::fetchDateFormat());
            $apiData[] = $element;
        }

        return $apiData;
    }

    private static function fetchDataFromApi() : string {
        $curl = curl_init(self::$apiUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // Github expects a user agent, no matter what it contains
        curl_setopt($curl, CURLOPT_USERAGENT, "Realitaetsverlust/Carbuncle");
        $apiData = curl_exec($curl);
        self::writeApiResponseToCache($apiData);

        return $apiData;
    }

    private static function fetchDataFromCache() : string {
        return file_get_contents(self::$cachePath);
    }

    private static function isCacheValid() {
        if(!file_exists(self::$cachePath)) {
            return false;
        }

        $file = new \SplFileInfo(self::$cachePath);

        //cache older than 12 hours
        if(time() - $file->getMTime() >= 43200) {
            return false;
        }

        return true;
    }

    private static function writeApiResponseToCache(string $response) {
        self::invalidateCache();

        file_put_contents(self::$cachePath, $response);
    }

    public static function invalidateCache() {
        if(file_exists(self::$cachePath)) {
            unlink(self::$cachePath);
        }
    }
}