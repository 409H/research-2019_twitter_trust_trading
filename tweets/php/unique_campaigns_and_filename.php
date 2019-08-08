<?php
/**
 * This gets the checksum of each images/raw and will count how many duplicates
 */
$path = __DIR__ ."/../dump/";
$unique = [];
foreach (glob($path ."/../images/raw/*") as $filename) {
    $hash = hash('sha256', file_get_contents($filename));
    if(isset($unique[$hash])) {
        continue;
    } else {
        $unique[$hash] = basename($filename);
        file_put_contents($path ."/../images/raw/unique/".basename($filename), file_get_contents($filename));
    }
}

arsort($unique);
file_put_contents($path ."/../unique_campaigns_and_filename.json", json_encode($unique));

echo PHP_EOL . "Fin." . PHP_EOL;