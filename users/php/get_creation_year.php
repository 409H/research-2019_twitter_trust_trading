<?php
/**
 * This gets all the creation date for all the profiles and gets the year
 * then counts how many of the profiles were created in that year
 */
$path = __DIR__ ."/../dump/";
$dates = [
    "user" => [],
    "year" => []
];
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
    $dates["user"][$json["id"]] = $objDateTime->format("Y-m-d");

    if(isset($dates["year"][$objDateTime->format("Y")])) {
        $dates["year"][$objDateTime->format("Y")] = $dates["year"][$objDateTime->format("Y")] + 1;
    } else {
        $dates["year"][$objDateTime->format("Y")] = 1;
    }
}

ksort($dates);
file_put_contents($path ."/../creation_year.json", json_encode($dates["year"]));

echo PHP_EOL . "Fin." . PHP_EOL;