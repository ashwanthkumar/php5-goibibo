<?php

define("API_BASE_URL", "https://developer.goibibo.com");
define("FORMAT", "json");
define("DEBUG", TRUE);

define("CLASS_E", "E");
define("CLASS_B", "B");
define("CLASS_NA", "NA");

define("VERTICAL_BUS", "bus");
define("VERTICAL_FLIGHT", "flight");

define("MODE_ONE", "one");
define("MODE_ALL", "all");

/**
	PHP5 Client for GoIbibo API

	- Full documentation on the response attributes can be found on 
	  https://developer.goibibo.com/docs
	- You need valid ApplicationId (app_id) and ApplicationKey (app_key) from
	  https://developer.goibibo.com/docs
	
*/
class GoIbibo {
	private $appId;
	private $appKey;

	public function __construct($appId, $appKey) {
		$this->appId = $appId;
		$this->appKey = $appKey;
	}

	function searchFlights($sourceInIATA, $destinationInIATA, $dateOfDeparture, 
		$dateOfArrival = NULL, $seatingClass = CLASS_E, $nrOfAdults = 1, 
		$nrOfChildren = 0, $nrOfInfants = 0) {

			$params = array();

			$params['source'] = $sourceInIATA;
			$params['destination'] = $destinationInIATA;
			$params['dateofdeparture'] = $dateOfDeparture;
			if(!is_null($dateOfArrival)) $params['dateofarrival'] = $dateOfArrival;
			$params['seatingclass'] = $seatingClass;
			$params['adults'] = $nrOfAdults;
			$params['children'] = $nrOfChildren;
			$params['infants'] = $nrOfInfants;

			return $this->makeRequest("/api/search/", $params);
	}

	function getMinimumFare($sourceInIATA, $destinationInIATA, 
		 $startDate, $endDate = NULL, $vertical = VERTICAL_FLIGHT,
		 $mode = MODE_ONE, $class = CLASS_NA) {
		
		$params = array();

		$params['vertical'] = $vertical;
		$params['source'] = $sourceInIATA;
		$params['destination'] = $destinationInIATA;
		$params['mode'] = $mode;
		$params['sdate'] = $startDate;
		if(!is_null($endDate)) $params['edate'] = $endDate;
		$params['class'] = $class;
		return $this->makeRequest("/api/stats/minfare/", $params);
	}

	function searchBuses($source, $destination, $dateOfOnwardTravel, 
		$dateOfArrival = NULL) {
			$params = array();

			$params['source'] = $source;
			$params['destination'] = $destination;
			$params['dateofdeparture'] = $dateOfOnwardTravel;
			if(!is_null($dateOfArrival)) $params['dateofarrival'] = $dateOfArrival;

			return $this->makeRequest("/api/bus/search", $params);
	}

	function getBusSeatLayout($busId) {
		return $this->makeRequest("/api/bus/seatmap/", array('skey' => $busId));
	}

	private function makeRequest($end_point, $params = array()) {
	    if(!function_exists('curl_init')) throw new Exception('cURL is not installed on your server');

	    $params['app_key'] = $this->appKey;
	    $params['app_id'] = $this->appId;
	    $params['format'] = FORMAT;
	    $request_url = API_BASE_URL . $end_point . "?" . http_build_query($params);

	    debug("Request URL: " . $request_url);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $request_url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        /*
        	developer.goibibo.com's SSL certificate has issue. 
        */
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        if(DEBUG) curl_setopt($curl, CURLOPT_VERBOSE, TRUE);


        $response_from_server = curl_exec($curl);
        return json_decode($response_from_server);
	}

}


function debug($message) {
	if(DEBUG) {
		echo "[DEBUG] " . date("r") . " => " . $message . "\n";
	}
}
