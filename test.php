<?php
define('__ROOT__', dirname(__FILE__));
require_once(__ROOT__.'/lib/road.php');
require_once(__ROOT__.'/lib/angle.php');


$lat =(double) $argv[1];
$lng =(double) $argv[2];

$angle = 0.0;
//$point = get_snapped_point($lat, $lng);
//$angle = getRotateAngle($lat, $lng, $point["latitude"], $point["longitude"]);
//Var_dump($angle);

/*
$api_key="123";
$url_arr = array("https://roads.googleapis.com/v1/nearestRoads?points=$lat,$lng&key=".$api_key,
                 "https://roads.googleapis.com/v1/snapToRoads?path=$lat,$lng&key=".$api_key);

$url= $url_arr[array_rand($url_arr)];
var_dump($url) ;

$add_txt_array= explode ("|", "15 WERTHEIM COURT UNIT 302|RICHMOND HILL, ON L4B3H7");
$txt_array= explode( " ",$add_txt_array[0]);
$dst_array= array_slice ($txt_array, 2);
var_dump(implode (" " ,$dst_array));
*/

/*
$url = "https://en.wikipedia.org/wiki/List_of_Toronto_subway_stations";
$page = file_get_contents($url, false);
//var_dump (trim ($page));

preg_match_all('/<th scope="row"><a href="(.*?)" title=(.*?)>(.*?)<\/a><\/th>/', trim($page), $matches);

$final=array();
foreach ($matches[1] as $key=> $sub_url)
{
    $str = file_get_contents ("https://en.wikipedia.org".$sub_url, false);
    preg_match ('/<span class="geo">(.*?)<\/span>/', trim($str), $geo);
    array_push ($final ,array ("name"=>$matches[3][$key], "geo" => $geo[1]));
    
}

file_put_contents("lib/stations.json",json_encode($final));
*/


function address ($address,...$others)
{
    
    echo( $address);
    return false;
}

/*
filter by orientation
for north-faceing angle should be between 90 to 270
*/
function orientation ($address,$lat, $lng)
{
    echo("o");
    return false;


}

//filter by nearest subway stations
function subway ($address,$lat, $lng)
{
    echo("s");
    return false;
    
}


function filter ($address,$lat, $lng)
{
    $filter_arr= array ("address"=>false, "subway"=> false, "orientation"=> true);
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

filter ("awwwwwtony",1 ,2);

?>

