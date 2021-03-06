<?php

namespace Realitaetsverlust\Carbuncle;

use DirectoryIterator;
use Iterator;

/**
 * List of proton versions
 */
class ProtonRepo implements Iterator {
    private array $repos;
    private string $repoPath;

    private int $position = 0;

    public function __construct(private string $activeRepo = "") {
        if($this->activeRepo === "") {
            $this->activeRepo = Config::getCurrentRepo();
        }

        $repo = str_replace('~', $_SERVER['HOME'], Config::fetchRepositories()->{$this->activeRepo});
        $this->repoPath = $repo;
        $directory = new DirectoryIterator($repo);

        $id = 0;
        foreach($directory as $file) {
            if ($file->isDot()) {
                continue;
            }

            // Check if the file "proton" exists in the folder. If not, it's probably not a proton version and should be ignored
            if(!file_exists($repo . '/' . $file . '/' . 'proton')) {
                continue;
            }

            $protonVersion = new Proton($id, $file->getFilename(), $file->getMTime(), $repo . '/' . $file);
            $this->addVersionToRepo($protonVersion);
            $id++;
        }
    }

    private function addVersionToRepo(Proton $proton) : void {
        $this->repos[] = $proton;
    }

    public function removeVersionFromFilesystem(int $id) : bool {
        if(!isset($this->repos[$id])) {
            StreamWriter::write('The given ID does not exist! Please provide a valid ID.');
            return false;
        }

        $versionToRemove = $this->repos[$id];

        if(!$versionToRemove->removeFromFilesystem()) {
            StreamWriter::write('Carbuncle was unable to delete this version. Maybe the permissions are incorrect?');
            return false;
        }

        unset($this->repos[$id]);
        $this->repos = array_values($this->repos);
        return true;
    }

    // TODO: Do this you lazy fuck
    public function isVersionInstalled(string $versionName) : bool {
        if(is_dir($this->repoPath . '/' . $versionName)) {
            return true;
        }

        return false;
    }

    public function getRepoPath() : string {
        return $this->repoPath;
    }

    //region Iterator-Methods
    public function current() : mixed
    {
        return $this->repos[$this->position];
    }

    public function next() : void
    {
        $this->position++;
    }

    public function key() : mixed
    {
        return $this->position;
    }

    public function valid() : bool
    {
        return isset($this->repos[$this->position]);
    }

    public function rewind() : void
    {
        $this->position = 0;
    }
    //endregion
}