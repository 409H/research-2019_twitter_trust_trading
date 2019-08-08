<?php
/**
 * This gets all the creation date for all the profiles and gets the month for a specific year
 * then counts how many of the profiles were created in that month
 */
$path = __DIR__ ."/../dump/";
$months = [];
if(isset($argv[1]) === false) {
    die("\r\nSupply a year as a parameter\r\n");
}
foreach (glob($path ."/*.json") as $filename) {
    $json = file_get_contents($filename);
    $json = json_decode($json, true);
    if(isset($json["errors"])) {
        continue;
    }

    if(isset($json["created_at"]) === false) {
        echo PHP_EOL . "Filename: ". $filename . PHP_EOL;
        continue;
    }

    $objDateTime = \DateTime::createFromFormat('D M d H:i:s O Y', $json["created_at"]);

    if($objDateTime->format("Y") === $argv[1]) {
        if(isset($months[$objDateTime->format("M")])) {
            $months[$objDateTime->format("M")] = $months[$objDateTime->format("M")] + 1;
        } else {
            $months[$objDateTime->format("M")] = 1;
        }
    }
}

ksort($months);
file_put_contents($path ."/../creation_month_{$argv[1]}.json", json_encode($months));

echo PHP_EOL . "Fin." . PHP_EOL;