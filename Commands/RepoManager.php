<?php

namespace Realitaetsverlust\Carbuncle;

use CommandInterface;

class RepoManager extends BaseCommand implements CommandInterface {
    public function exec(array $arguments = []) {
        switch($arguments[0]) {
            case 'add':
                Config::addRepo($arguments[1], $arguments[2]);
                break;
            case 'remove':
                Config::removeRepo($arguments[1]);
                break;
            case 'show':
                $this->showRepos();
                break;
            case 'set':
                Config::setCurrentRepo($arguments[1]);
                break;
        }
    }

    private function showRepos(array $arguments = []) : void {
        $repos = Config::fetchRepositories();

        // TODO: Move table representation to StreamWriter

        $usedRepo = Config::getCurrentRepo();
        $output = "\nYou are currently using the \"{$usedRepo}\" repository\n\n";

        $maxIdLength = 0;
        $maxRepoPathLength = 0;
        foreach($repos as $name => $path) {
            $maxIdLength = max($maxIdLength, strlen($name));
            $maxRepoPathLength = max($maxRepoPathLength, strlen($path));
        }

        $lines = [];
        $maxLineLength = 0;
        foreach($repos as $name => $path) {
            $paddedId = str_pad($name, $maxIdLength, " ", STR_PAD_RIGHT);
            $paddedPath = str_pad($path, $maxRepoPathLength, " ", STR_PAD_RIGHT);
            $line = "| {$paddedId} | {$paddedPath} |\n";
            $maxLineLength = max($maxLineLength, strlen($line));
            $lines[] = $line;
        }

        $tableSeperator = str_pad("", $maxLineLength, "-") . "\n";
        $headlineIdElement = str_pad("ID:", $maxIdLength, " ",STR_PAD_RIGHT);
        $headlinePathElement = str_pad("Path:", $maxRepoPathLength, " ", STR_PAD_RIGHT);
        $headline = "| {$headlineIdElement} | {$headlinePathElement} |\n";

        $output .= $tableSeperator . $headline . $tableSeperator . implode($lines) . $tableSeperator;

        fwrite(STDOUT, $output . PHP_EOL);
    }
}