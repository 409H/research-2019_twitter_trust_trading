<?php
/**
 * This gets the count of statuses for each profile and counts
 * the number of profiles with the same amount of statuses
 */
$path = __DIR__ ."/../dump/";
$tweet_count = [];
foreach (glob($path ."/*.json") as $filename) {
    $json = file_get_contents($filename);
    $json = json_decode($json, true);
    if(isset($json["errors"])) {
        continue;
    }

    if(isset($json["statuses_count"]) === false) {
        continue;
    }

    if(isset($tweet_count[$json["statuses_count"]])) {
        $tweet_count[$json["statuses_count"]] = $tweet_count[$json["statuses_count"]] + 1;
    } else {
        $tweet_count[$json["statuses_count"]] = 1;
    }
}

ksort($tweet_count);
file_put_contents($path ."/../tweet_count.json", json_encode($tweet_count));

echo PHP_EOL . "Fin." . PHP_EOL;