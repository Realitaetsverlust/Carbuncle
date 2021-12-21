<?php

namespace Realitaetsverlust\Carbuncle;

use CommandInterface;
use PharData;

/**
 * Installation command. Installs a given proton version
 */
class Install extends BaseCommand implements CommandInterface {
    public function exec(array $arguments = []) {
        $id = readline("Which version would you like to install (ID via 'carbuncle releases'): ");

        $version = Api::fetchVersionById($id);
        $name = $version->name;
        $downloadUrl = $version->downloadUrl;

        $prompt = readline("Downloading {$name}, proceed? (y/n) ");

        if($prompt != "y") {
            fwrite(STDOUT, "Confirmation failed, aborted.".PHP_EOL);
            return false;
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

        file_put_contents($archivePath, file_get_contents($downloadUrl, false, $context));
        StreamWriter::write("Download successful.");

        $repo = new ProtonRepo($this->activeRepo);
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

        return true;
    }
}