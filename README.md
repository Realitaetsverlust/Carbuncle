# Carbuncle

Carbuncle is a fast and reliable cli tool used to manage proton-GE versions. It is extremely lightweight and has no dependencies (Repository has only 83.2 KiB)

## Requirements:
- PHP 8 or greater
- JSON-Extension and ZLIB-Extension (usually distributed by default)

## Installation
- Clone the repository
- Execute `php install_carbuncle.php`. This will create a small bash script which will be installed into `/usr/local/bin`. If you don't want that for whatever reason, you can also put `alias carbuncle=/path/to/carbuncle.php` into your bashfile. 

If you're using an arch based distro, you may use the AUR package ... that I have not yet created.

### WHY PHP???!?!?!?!?!?! ITS SO BAD!!!!!
To get the eleph(p)ant out of the room first: If you don't use an application because of the language it's written in, you're a dumbass. You can write great programs in brainfuck and malbolge while you can write terrible programs in C++ or C#. The language used does rarely impact the quality of the software itself. Hating my application because it uses PHP is just as stupid as hating virt-manager for being written in python.

I chose PHP because it's fast and I'm very familiar with it. I wrote this application within ~6 hours while travelling to my parents for christmas and I didn't want to learn any other language for it.

If you don't want to use it because of PHP, that's fine. You might want to take a look at protonup, which is written in python.

## Usage:

### Display all releases:

```sh
carbuncle releases list
```

This will show all the latest releases, like this:

```
|-----------------------------------------------------------------------|
| ID: | Name of Release:                    | Published at: | Installed |
|-----------------------------------------------------------------------|
| 0   | Proton-7.0rc2-GE-1                  | 19.12.2021    |     ✓     |
| 1   | Proton-6.21-GE-2                    | 16.11.2021    |     ✓     |
| 2   | Proton-6.21-GE-1                    | 13.11.2021    |           |
 .......................................................................
| 29  | Proton-5.9-GE-6-ST                  | 17.09.2020    |           |
|-----------------------------------------------------------------------|
```

Note that these are NOT your local installations, but all the available ones from github.

### Display all installed versions
To view all installed versions, use

```shell
carbuncle version list
```

### Install a release

If you want to install a release, use the following:

```shell
carbuncle version install
```

This will prompt you to enter the ID of the version you want to install. You can see the ID at the start of each row.

A full installation process should look like this:

```
$ carbuncle version install
Which version would you like to install (ID via 'carbuncle releases'): 0
Downloading Proton-7.0rc2-GE-1, proceed? (y/n) y
Downloading Proton-7.0rc2-GE-1 from github.
Download successful.
Extracting archive to /home/realitaetsverlust/.steam/root/compatibilitytools.d.
Cleaning up archive
```

### Removing a version

You can easiely remove a version via 

```shell
carbuncle version remove
```

As with the install command, this will prompt you to pass an ID (obtainable via ``carbuncle version list``), which will then be removed from your system.

## Multiple repositories
By default, carbuncle uses `~/.steam/root/compatiblitytools.d` as install path. However, you have the option to add your own "repositories" which will then be used for installations. This is useful if you installed steam via flatpak or if you want to use another repository for development reasons, for example.

### Show all repositories
You can view all currently configured repositories with

```
carbuncle repo list
```

### Adding a new repository

You can add a new repository via 
```
carbuncle repo add name /path/to/repository
```

If you add a folder that already contains Proton-Versions, they are detected automatically.

### Removing a repository

You can remove a repository with
```
carbuncle repo remove name
```

### Setting the current repository

Setting the currently used repository is done with

```
carbuncle repo use name
```

## Cache

In order to avoid too many requests to the github API, results are stored for 12 hours within `~/.config/realitaetsverlust/carbuncle/cache.json`. 12 hours usually isn't that much of an issue, but if it results in problems, you can clear the cache by deleting the cache file or by simply executing

```shell
carbuncle cache clear
```

