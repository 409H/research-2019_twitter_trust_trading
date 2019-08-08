<?php
/**
 * Fetches the geoip information from urlscan for each domain 
 * associated with Twitter trust trading (in ../domains.json)
 */
$path = __DIR__ ."/";
$ini = parse_ini_file(__DIR__ ."/config.ini", true);
$geoips = [];

$domains = json_decode(file_get_contents($path ."/../domains.json"), true);
foreach($domains as $domain) {
    $domain = str_replace("www.", null, $domain);
    $url = "https://urlscan.io/api/v1/search/?q=domain:{$domain}";
    $ch = curl_init($url);
    $fp = fopen("/", "r");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    fclose($fp);

    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if(in_array($httpcode, [200])) {
        $json = json_decode($output, true);  

        if(count($json["results"])) {
            foreach($json["results"] as $result) {
                if(strtolower($result["page"]["domain"]) === strtolower($domain)) {
                    $country = strtoupper($result["page"]["country"]);
                    $geoips[$country][] = $domain;
                    break;
                }
            }
        } else {
            echo PHP_EOL . "No results for {$domain}". PHP_EOL;
        }

        sleep(1);
        curl_close($ch);
    }
}

file_put_contents($path ."/../geoips.json", json_encode($geoips));

echo PHP_EOL . "Fin." . PHP_EOL;