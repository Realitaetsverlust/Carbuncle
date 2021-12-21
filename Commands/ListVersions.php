<?php

namespace Realitaetsverlust\Carbuncle;

use CommandInterface;

/**
 * ListVersions command. Lists all available versions installed on the file system
 */
class ListVersions extends BaseCommand implements CommandInterface {
    public function exec(array $arguments = []) : void {
        $protonRepo = new ProtonRepo($this->activeRepo);
        // TODO: Move table representation to StreamWriter
        $this->displayVersionsAsTable($protonRepo);
    }

    private function displayVersionsAsTable(ProtonRepo $protonRepo) : void {
        $maxNameLength = 0;
        foreach($protonRepo as $version) {
            $maxNameLength = max(strlen($version->filename), $maxNameLength);
        }

        $dateLength = strlen($version->dateModified);

        $i = 0;
        $maxTableLength = 0;
        $outputStrings = [];
        foreach($protonRepo as $version) {
            $paddedVersionName = str_pad($version->filename, $maxNameLength, " ", STR_PAD_RIGHT);

            $outputString = "| {$i}  | {$paddedVersionName} | {$version->getFormattedTime()} |\n";
            $maxTableLength = max(strlen($outputString), $maxTableLength);
            $outputStrings[] = $outputString;
            $i++;
        }
        $tableSeperator = str_pad("", $maxTableLength, "-") . "\n";
        $nameHeadlineElement = str_pad("Name of Version", $maxNameLength, " ", STR_PAD_BOTH);
        $dateHeadlineElement = str_pad("Installed", $dateLength, " ", STR_PAD_BOTH);
        $headline = "| id | {$nameHeadlineElement} | {$dateHeadlineElement} |\n";

        $output = $tableSeperator . $headline . $tableSeperator . implode($outputStrings) . $tableSeperator;

        fwrite(STDOUT, $output . PHP_EOL);
    }
}