<?php
/*
This is a tool filtering houses in Toronto by street type 
For example CRT,PL,CRES
Generally,houses locate on these streets will be quiet and beautiful
Run like this: php -f realor.php
*/

$url = "https://api2.realtor.ca/Listing.svc/PropertySearch_Post";
$params = array ("CultureId" => "1",
                 "ApplicationId" => "1", 
                 "ParkingSpaceTotal" => "4",
                 "RecordsPerPage" => "9",
                 "BuildingTypeId" => "1",
                 "ConstructionStyleId" => "3",
                 "PriceMin" => "1100000",
                 "PriceMax" => "2000000",
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
                 "SortOrder" => "A",
                 "SortBy" => "1",
                 "viewState" => "m",
                 "favouritelistingids" => "18347321",
                 "CurrentPage" => "1",
                 "ZoomLevel" => "12",
                 "PropertyTypeGroupID" => "1",
                 "Token" => "D6TmfZprLI8jgvCHNOnL5Jd3MNEO4z/E9t ClDCeGdM=",
                 "GUID" => "648f616c-8106-47df-86d7-456463b1f15f",
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

function my_house ($address)
{
    $address_arr= array ("PL","CRT");
    $ret=false;
    
    foreach ($address_arr as $add){
        if (strpos($address, $add))
        {
            $ret=true;
            break;
        }
    }
    return $ret;
}

$result_arr=json_decode($result,true);

if(!empty($result_arr)) {

    foreach ($result_arr["Results"] as $house){
        if (my_house ($house["Property"]["Address"]["AddressText"]))
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
            if (my_house ($house["Property"]["Address"]["AddressText"]))
            {
                array_push($house_arr,$house["MlsNumber"]);
            }            
        }        
    }
}


var_dump($house_arr);

?>
