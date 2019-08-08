<?php
if(isset($argv[1]) === false) {
    die("\r\nSupply a ticker as a parameter\r\n");
}

$path = __DIR__ ."/";
$ini = parse_ini_file(__DIR__ ."/config.ini", true);
$month_end = 8;
$month_avg = [];
for($i=1;$i<=$month_end;$i++) {
    $month_max_days = cal_days_in_month(CAL_GREGORIAN, $i, 2019);
    $url = "https://api.nomics.com/v1/exchange-rates/history?key={$ini["nomics"]["key"]}&currency={$argv[1]}&start=2019-0{$i}-01T00%3A00%3A00Z&end=2019-0{$i}-{$month_max_days}T00%3A00%3A00Z";
    $ch = curl_init($url);
    $fp = fopen("/", "r");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    fclose($fp);

    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if(in_array($httpcode, [200])) {
        $json = json_decode($output, true);  
        $month_prices = [];
        foreach($json as $p) {
            $month_prices[] = $p["rate"];
        }

        $month_avg[$i] = (array_sum($month_prices)/count($month_prices));
        sleep(1);
        curl_close($ch);
    }
}

file_put_contents($path ."/../market_avg_{$argv[1]}.json", json_encode($month_avg));

echo PHP_EOL . "Fin." . PHP_EOL;