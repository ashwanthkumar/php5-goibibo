# GoIbibo API (PHP5 Client)

PHP5 Client to [GoIbibo API](https://developer.goibibo.com/docs "GoIb Developer Portal"). 

### Usage

```
require_once("GoIbibo.php");

$api = new GoIbibo("__MY_APP_ID__","__MY_APP_KEY__");

// Get flights from Chennai to Banglore on 17th November, 2013
$api->searchFlights("MAA", "BLR", "20131117");

// Get minimum fares of flights from Chennai to Banglore
$api->getMinimumFare("MAA", "BLR", "20131117");

```


