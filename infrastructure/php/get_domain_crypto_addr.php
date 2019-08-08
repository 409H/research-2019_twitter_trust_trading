<?php
/**
 * Fetches the cryptocurrency addresses as per the entries on CryptoScamDB
 * for each domain associated with Twitter trust trading (in ../domains.json)
 */
$path = __DIR__ ."/";
$ini = parse_ini_file(__DIR__ ."/config.ini", true);
$addr = [];

$domains = json_decode(file_get_contents($path ."/../domains.json"), true);
foreach($domains as $domain) {
    $domain = str_replace("www.", null, $domain);
    $url = "https://api.cryptoscamdb.org/v1/domain/{$domain}";
    $ch = curl_init($url);
    $fp = fopen("/", "r");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    fclose($fp);

    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if(in_array($httpcode, [200])) {
        $json = json_decode($output, true);  

        if(count($json["result"])) {
            if(isset($json["result"][0]["addresses"])) {
                $addr[$domain] = $json["result"][0]["addresses"];
            }
        } else {
            echo PHP_EOL . "No results for {$domain}". PHP_EOL;
        }

        sleep(1);
        curl_close($ch);
    }
}

file_put_contents($path ."/../crypto_addrs.json", json_encode($addr));

echo PHP_EOL . "Fin." . PHP_EOL;