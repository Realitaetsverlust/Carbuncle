<?php

namespace Realitaetsverlust\Carbuncle;

abstract class BaseCommand {
    public function __construct(protected string $activeRepo) {

    }
}