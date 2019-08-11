<?php
/**
 * This gets the username input mask
 * and writes them to a JSON file
 * 
 * L = A-Za-z
 * 9 = 0-9
 */
$path = __DIR__ ."/../dump/";
$usernames = [
    "users" => [],
    "count" => []
];
foreach (glob($path ."*.json") as $filename) {
    $json = file_get_contents($filename);
    $json = json_decode($json, true);
    if(isset($json["errors"])) {
        continue;
    }

    $u = str_split($json["screen_name"]);
    $mask = "";
    foreach($u as $t) {
        if(preg_match("/[A-Za-z]/", $t)) {
            $mask .= "L";
        } elseif(preg_match("/[0-9]/", $t)) {
            $mask .= "9";
        } else {
            $mask .= $t;
        }
    }

    $usernames["users"][$json["screen_name"]] = $mask;
    if(isset($usernames["count"][$mask])) {
        $usernames["count"][$mask]++;
    } else {
        $usernames["count"][$mask] = 1;
    }
}

file_put_contents($path ."/../username_input_mask.json", json_encode($usernames));

echo PHP_EOL . "Fin." . PHP_EOL;