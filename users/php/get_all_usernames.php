<?php
/**
 * This gets all the usernames from the dump directory and 
 * writes them to a JSON file
 */
$path = __DIR__ ."/../dump/";
$usernames = [];
foreach (glob($path ."*.json") as $filename) {
    $json = file_get_contents($filename);
    $json = json_decode($json, true);
    if(isset($json["errors"])) {
        continue;
    }

    $usernames[] = $json["screen_name"];
}

file_put_contents($path ."/../usernames.json", json_encode($usernames));

echo PHP_EOL . "Fin." . PHP_EOL;