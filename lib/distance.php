<?php
/*
 Calculate transit distance (seconds) between your lot and subway stations
 Have limits to call google map api
*/
define('__DIR__', dirname(__FILE__));

function googleDistance ($lat1, $lng1, $lat2, $lng2, $mode)
{
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json",
            'method'  => 'GET' 
        )
    );
    
    //ATTENTION :Replace your own api_key
    $api_key="key";
    $url= "https://maps.googleapis.com/maps/api/directions/json?mode=$mode&origin=$lat1,$lng1&destination=$lat2,$lng2&key=".$api_key;
    $context  = stream_context_create($options);

    $result = file_get_contents($url, false, $context);
    $result_arr=json_decode($result,true);

    
    return ($result_arr["routes"][0]["legs"][0]["duration"]["value"]);
}

/*

Calculate distance between two geo points
*/

function distance ($lat1, $lng1, $lat2 ,$lng2)
{
    $p = 0.017453292519943295;
    $a = 0.5 - cos(($lat2 - $lat1) * $p)/2 + cos($lat1 * $p) * cos($lat2 * $p) * (1 - cos(($lng2 - $lng1) * $p)) / 2;
    return 12742 * asin(sqrt($a));
}

/*
  Get nearest subway station
*/
function getStation ($lat ,$lng)
{
    $stations_arr =json_decode(file_get_contents (__DIR__.'/stations.json') ,true);
  
    $distance=1e6;
    $k=0;
    foreach ($stations_arr as $key => $station)
    {
        $loc= explode(";",$station["geo"]);
        $dist = distance ($lat ,$lng, (double) trim($loc[0]), (double) trim($loc[1]));
        if ($distance > $dist)
        {
            $distance = $dist;
            $k= $key;   
        }
    }
    $ret = $stations_arr[$k];
    $ret ["distance"] =$distance;
    return $ret;
}

//var_dump(distance (43.8006949,-79.4455814,43.78056,-79.41472));

//var_dump(getstation (43.800927,-79.4343206));
?>