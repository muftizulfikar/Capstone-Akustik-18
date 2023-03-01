<?php
function getLastData($id)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://firebase17.herokuapp.com/api/v1/getLastData?id=' . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    // Return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    return $result;
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
}


//set map api url
$url = "https://firebase17.herokuapp.com/api/v1/getLastData?id=3";

//call api
$json = file_get_contents($url);
echo $json;
$json = json_decode($json);
$lat = $json->medium;
var_dump($lat);

$address = "Brooklyn+NY+USA";

//set map api url
$url = "http://maps.google.com/maps/api/geocode/json?address=$address";

//call api
$json = file_get_contents($url);
echo $json;
$json = json_decode($json);
$lat = $json->status;
echo $lat;
