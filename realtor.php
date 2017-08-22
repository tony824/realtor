<?php
/*
This is a tool filtering houses in Toronto by street type 
For example CRT,PL,CRES
Generally,houses locate on these streets will be quiet and beautiful
Run like this: php -f realor.php
*/
define('__ROOT__', dirname(__FILE__));
require_once(__ROOT__.'/lib/road.php');
require_once(__ROOT__.'/lib/angle.php');

$url = "https://api2.realtor.ca/Listing.svc/PropertySearch_Post";
$params = array ("CultureId" => "1",
                 "ApplicationId" => "1", 
                 "ParkingSpaceTotal" => "4",
                 "RecordsPerPage" => "9",
                 "BuildingTypeId" => "1",
                 "ConstructionStyleId" => "3",
                 "PriceMin" => "1200000",
                 "PriceMax" => "1600000",
                 "MaximumResults" => "9",
                 "PropertySearchTypeId" => "1",
                 "TransactionTypeId" => "2",
                 "StoreyRange" => "0-0",
                 "BedRange" => "3-0",
                 "BathRange" => "3-0",
                 "LongitudeMin" => "-79.65129996230468",
                 "LongitudeMax" => "-79.23862601210936",
                 "LatitudeMin" => "43.702077668041646",
                 "LatitudeMax" =>"43.923312715190676",
                 "SortOrder" => "D",
                 "SortBy" => "6",
                 "viewState" => "m",
                 "favouritelistingids" => "18347321",
                 "CurrentPage" => "1",
                 "ZoomLevel" => "12",
                 "PropertyTypeGroupID" => "1",
                 "Version" => "6.0" );
$data = http_build_query ($params);
$data_len = strlen ($data);
$opts = array('http' =>
              array(
                  'method'  => 'POST',
                  'header'  => "Content-Type: application/x-www-form-urlencoded\r\nContent-Length: $data_len\r\n",
                  'content' => $data 
              )
);

$context  = stream_context_create($opts);
$result = file_get_contents($url, false, $context);

$house_arr=array ();

//filter by address name
function address ($address)
{
    //return true;
    //get ride of street name,focus on street type
    $add_arr= explode ("|", $address);
    $txt_array= explode ( " ",$add_arr[0]);
    $dst_array= array_slice($txt_array, 2);
    $address_str= implode (" " ,$dst_array);
    
    $address_arr= array ("PL","CRT","CRES","LANE","CIRC");
    $ret=false;
    
    foreach ($address_arr as $add){
        if (strpos($address_str, $add))
        {
            $ret=true;
            break;
        }
    }
    return $ret;
}

//filter by orientation
function orientation ($lat, $lng)
{
    $angle=0.0;
    $point = get_snapped_point($lat, $lng);
    $angle = getRotateAngle($lat, $lng, $point["latitude"], $point["longitude"]);
    return (($angle >105) && ($angle<255));
}

$result_arr=json_decode($result,true);
//var_dump ($result_arr);
//exit();

if(!empty($result_arr)) {

    foreach ($result_arr["Results"] as $house){
        if ((address ($house["Property"]["Address"]["AddressText"]))
            && orientation ((double)$house["Property"]["Address"]["Latitude"],
                            (double)$house["Property"]["Address"]["Longitude"]))
        {
            array_push($house_arr,$house["MlsNumber"]);
        }            
    }
    
    for ($page=2; $page<=$result_arr["Paging"]["TotalPages"]; $page++)
    {
        $params["CurrentPage"]=$page;
        $data = http_build_query ($params);
        $data_len = strlen ($data);
        $opts = array('http' =>
                      array(
                          'method'  => 'POST',
                          'header'  => "Content-Type: application/x-www-form-urlencoded\r\nContent-Length: $data_len\r\n",
                          'content' => $data 
                      )
        );

        $context  = stream_context_create($opts);
        $ret = file_get_contents($url, false, $context);
        $ret_arr=json_decode($ret,true);
        foreach ($ret_arr["Results"] as $house){
            if ((address($house["Property"]["Address"]["AddressText"]))
                && orientation ((double)$house["Property"]["Address"]["Latitude"],
                                (double)$house["Property"]["Address"]["Longitude"]))
            {
                array_push($house_arr,$house["MlsNumber"]);
            }            
        }        
    }
}


var_dump($house_arr);

?>
