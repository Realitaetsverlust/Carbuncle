<?php

interface CommandInterface {
    public function exec(array $arguments = []);
}