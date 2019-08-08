<?php
/**
 * This gets the checksum of each images/raw and will match it with the input to get
 * the image that matches.
 */
if(isset($argv[1]) === false) {
    die("\r\nSupply a sha256 parameter\r\n");
}
$needle = $argv[1];
$path = __DIR__ ."/../dump/";
foreach (glob($path ."/../images/raw/*") as $filename) {
    $hash = hash('sha256', file_get_contents($filename));
    if($hash === $needle) {
        echo PHP_EOL . $filename . PHP_EOL;
        break;
    }
}

echo PHP_EOL . "Fin." . PHP_EOL;