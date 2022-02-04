<?php

namespace Realitaetsverlust\Carbuncle;

use CommandInterface;

/**
 * Releases command. Displays all releases available
 */
class ReleasesManager extends BaseCommand implements CommandInterface {
    public function exec(array $arguments = []) : void {
        switch($arguments[0]) {
            case 'list':
                $data = Api::fetchData();
                // TODO: Move table representation to StreamWriter
                $this->displayReleasesAsTable($data);
                break;
            default:
                StreamWriter::write("The command \"{$arguments[0]}\" does not exist!");
                break;
        }
    }

    private function displayReleasesAsTable(array $releases) : void {
        $maxNameLength = 0;
        $releaseCountLength = ($length = strlen(count($releases))) < 3 ? 3 : $length;
        $protonRepo = new ProtonRepo();
        foreach($releases as $version) {
            $maxNameLength = max(strlen($version->name), $maxNameLength);
        }

        $dateLength = max(strlen($version->publishedAt), strlen("Published at:"));

        $i = 0;
        $outputStrings = [];
        foreach($releases as $version) {
            $paddedId = str_pad($i, $releaseCountLength, " ", STR_PAD_RIGHT);
            $paddedReleaseName = str_pad($version->name, $maxNameLength, " ", STR_PAD_RIGHT);
            $paddedDate = str_pad($version->publishedAt, $dateLength, " ", STR_PAD_RIGHT);
            $isInstalled = $protonRepo->isVersionInstalled($version->name) ? "✓" : " ";

            $outputString = "| {$paddedId} | {$paddedReleaseName} | {$paddedDate} |     {$isInstalled}     |\n";
            $outputStrings[] = $outputString;
            $i++;
        }

        $maxTableLength = strlen($outputString);

        $tableSeperator = '|' . str_pad("", $maxTableLength - 2, "-") . "|\n";
        $idHeadlineElement = str_pad("ID:", $releaseCountLength, " ", STR_PAD_RIGHT);
        $nameHeadlineElement = str_pad("Name of Release:", $maxNameLength, " ", STR_PAD_RIGHT);
        $dateHeadlineElement = str_pad("Published at:", $dateLength, " ", STR_PAD_RIGHT);
        $headline = "| {$idHeadlineElement} | {$nameHeadlineElement} | {$dateHeadlineElement} | Installed |\n";

        $output = $tableSeperator . $headline . $tableSeperator . implode($outputStrings) . $tableSeperator;

        StreamWriter::write($output);
    }
}