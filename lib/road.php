<?php
/*
 Give a lot's Geo info (lat lng)
 Use google map road api to get snapped points (lat1 lng1) on the nearlest road of the given lot
 Use vector (lng1 lat1)--->(lng1 lat1) to caculate orientation
*/

function get_snapped_point ($lat, $lng)
{
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json",
            'method'  => 'GET' 
        )
    );
    
    //ATTENTION :Replace your own api_key
    $api_key="google_map_api_key";
    $url = "https://roads.googleapis.com/v1/nearestRoads?points=$lat,$lng&key=".$api_key;
    $context  = stream_context_create($options);

    $result = file_get_contents($url, false, $context);
    $result_arr=json_decode($result,true);

    
    return ($result_arr["snappedPoints"][0]["location"]);
}

//var_dump(get_snapped_point (43.8006949,-79.4455814));

?>
