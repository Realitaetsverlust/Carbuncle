<?php

$carbuncleLocation = __DIR__ . '/carbuncle.php';

$fileContent = <<<EOF
#!/bin/bash

"$carbuncleLocation" "$@"
EOF;

file_put_contents("/usr/local/bin/carbuncle", $fileContent);
chmod("/usr/local/bin/carbuncle", 0755);
