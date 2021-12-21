<?php

namespace Realitaetsverlust\Carbuncle;

use PharData;
use CommandInterface;

class VersionManager extends BaseCommand implements CommandInterface {
    public function exec(array $arguments = []) {
        switch($arguments[0]) {
            case 'install':
                $this->installVersion();
                break;
            case 'remove':
                $this->removeVersion();
                break;
            case 'list':
                $this->showInstalledVersions();
                break;
            default:
                StreamWriter::write("The command \"{$arguments[0]}\" does not exist");
                break;
        }
    }

    private function showInstalledVersions() {
        $protonRepo = new ProtonRepo();
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

    private function installVersion() : void {
        $id = readline("Which version would you like to install (ID via 'carbuncle releases'): ");

        $version = Api::fetchVersionById($id);
        $name = $version->name;
        $downloadUrl = $version->downloadUrl;

        $prompt = readline("Downloading {$name}, proceed? (y/n) ");

        if($prompt != "y") {
            StreamWriter::write("Confirmation failed, aborting.");
            exit();
        }

        StreamWriter::write("Downloading {$name} from github.");

        $archivePath = "/tmp/{$name}.tar.gz";

        $context = stream_context_create(array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Accept-language: en\r\n" .
                    "User-Agent: Realitaetsverlust/Carbuncle" // Github expects a user agent, no matter what it contains
            )
        ));

        // TODO: Some kind of loading bar or progress spinner
        file_put_contents($archivePath, file_get_contents($downloadUrl, false, $context));
        StreamWriter::write("Download successful.");

        $repo = new ProtonRepo();
        StreamWriter::write("Extracting archive to {$repo->getRepoPath()}.");

        $p = new PharData($archivePath);
        $p->decompress();
        $phar = new PharData("/tmp/{$name}.tar");
        $phar->extractTo($repo->getRepoPath());

        if(!file_exists($repo->getRepoPath() . '/' . $name)) {
            StreamWriter::write('Something went wrong during the download process.');
            exit();
        }

        StreamWriter::write("Cleaning up archive");

        unlink($archivePath);
        unlink("/tmp/{$name}.tar");
    }

    private function removeVersion() : void {
        $id = readline("Which version would you like to install (ID via 'carbuncle list-versions'): ");
        $repo = new ProtonRepo();
        if (!$repo->removeVersionFromFilesystem($id)) {
            StreamWriter::write('Removal of given version failed');
            exit();
        }

        StreamWriter::write('Version successfully removed!');
    }
}