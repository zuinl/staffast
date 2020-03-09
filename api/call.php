<?php

    //Teste de chamada da API teste.php desta mesma pasta

    //URL da API
    $latitude = "-22.9982128";
    $longitude = "-45.5284302";
    //$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$latitude.",".$longitude."&key=AIzaSyBs89DVkWnqf6u_CxyNXVg9NKgH3c5sEco";
    
    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);

    var_dump($resp);

    echo $resp['status'];
    echo $resp['results'][0]['formatted_address'];
?>