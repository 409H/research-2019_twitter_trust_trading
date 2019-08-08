<?php
/**
 * This gets any of the timezone values in the user profile, if they set it up
 * it then counts how many profiles are in the same time zone
 */
$path = __DIR__ ."/../dump/";
$t = [];
foreach (glob($path ."/*.json") as $filename) {
    $json = file_get_contents($filename);
    $json = json_decode($json, true);
    if(isset($json["errors"])) {
        continue;
    }

    if(isset($json["time_zone"]) === false) {
        continue;
    }

    if(isset($t[$json["time_zone"]])) {
        $t[$json["time_zone"]] = $t[$json["time_zone"]] + 1;
    } else {
        $t[$json["time_zone"]] = 1;
    }
}

ksort($t);
file_put_contents($path ."/../timezone.json", json_encode($t));

echo PHP_EOL . "Fin." . PHP_EOL;