<?php
/*
This is a tool filtering houses in Toronto
Run like this: php -f realor.php
*/
define('__ROOT__', dirname(__FILE__));
require_once(__ROOT__.'/lib/road.php');
require_once(__ROOT__.'/lib/angle.php');
require_once(__ROOT__.'/lib/distance.php');

$url = "https://api2.realtor.ca/Listing.svc/PropertySearch_Post";
$params = array ("CultureId" => "1",
                 "ApplicationId" => "1", 
                 "ParkingSpaceTotal" => "4",
                 "RecordsPerPage" => "9",
                 "BuildingTypeId" => "1",
                 "ConstructionStyleId" => "3",
                 "PriceMin" => "1200000",
                 "PriceMax" => "1750000",
                 "MaximumResults" => "9",
                 "PropertySearchTypeId" => "1",
                 "TransactionTypeId" => "2",
                 "StoreyRange" => "0-0",
                 "BedRange" => "3-0",
                 "BathRange" => "2-0",
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

/*
filter by street type name
For example CRT,PL,CRES
Generally,houses locate on these streets will be quiet and beautiful
*/
function address ($address,...$others)
{
    //get rid of sreet No.,street name,focus on street type
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

/*
filter by orientation
for north-faceing angle should be between 90 to 270
*/
function orientation ($address,$lat, $lng)
{
    $angle=0.0;
    $point = get_snapped_point($lat, $lng);
    $angle = getRotateAngle($lat, $lng, $point["latitude"], $point["longitude"]);
    return (($angle >105) && ($angle<255));
}

//filter by nearest subway stations
function subway ($address,$lat, $lng)
{
    $ret = false;
    $station = getStation ($lat ,$lng);
    if ($station["distance"] < 1.0)
    {
        $ret = true;
    }
    return $ret;
}


function filter ($address,$lat, $lng)
{
    $filter_arr= array ("address"=>false, "subway"=> true, "orientation"=> true);
    $ret= true;
    foreach($filter_arr as $key=> $value)
    {
        if ($value)
        {
            $ret &= call_user_func($key, $address,$lat,$lng);
        }
    }
    return $ret;
}

$result_arr=json_decode($result,true);
//var_dump ($result_arr);
//exit();

if(!empty($result_arr)) {

    foreach ($result_arr["Results"] as $house){
        $lat = (double)$house["Property"]["Address"]["Latitude"];
        $lng = (double)$house["Property"]["Address"]["Longitude"];
        $add = $house["Property"]["Address"]["AddressText"];

        if (filter ($add,$lat,$lng))
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
            $lat = (double)$house["Property"]["Address"]["Latitude"];
            $lng = (double)$house["Property"]["Address"]["Longitude"];
            $add = $house["Property"]["Address"]["AddressText"];

            if (filter ($add,$lat,$lng))
            {
                array_push($house_arr,$house["MlsNumber"]);
            }            
        }        
    }
}


var_dump($house_arr);

?>
