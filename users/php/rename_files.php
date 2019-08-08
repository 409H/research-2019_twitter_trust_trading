<?php
/**
 * The data gathering process pulls user profiles and names them "YYYY-MM-DD--{USERNAME}.json"
 * This renames the files to be {USERNAME}.json
 */
$path = __DIR__ ."/../dump/";
foreach (glob($path . "*") as $filename) {
   $f = basename($filename);
   preg_match("/^\d{4}\-\d{2}\-\d{2}\-\-(.*)$/", $f, $matches);
   if(count($matches) > 0) {
       if(file_exists($path . $matches[1])) {
           unlink($filename);
           continue;
       }

       file_put_contents(($path . $matches[1]), file_get_contents($filename));
       unlink($filename);
   }
}

echo PHP_EOL . "Fin." . PHP_EOL;
