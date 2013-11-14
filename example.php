<?php

require_once("GoIbibo.php");
$api =  new GoIbibo("__APP_ID__", "__APP_KEY__");

$flights = $api->searchFlights("MAA", "BLR", "20131117");
print_r($flights);
