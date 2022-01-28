<?php
//info: other options https://github.com/public-apis/public-apis#weather
//TODO: change to this: https://goweather.herokuapp.com/weather/22182

$url = 'https://goweather.herokuapp.com/weather/22182';

$options = array(
    CURLOPT_HEADER => false,
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false
);

$ch = curl_init();
curl_setopt_array($ch, $options);
$response = curl_exec($ch);
curl_close($ch);

$return_data = json_decode($response, true);
print_r("temp:" . (9/5*floatval($return_data['temperature'])+32));


//print_r('<p>this is the processed' .$return_data);
//print_r("<p>TEMP1: " . ($return_data  -> {'current_observation'} ));
//print_r("<p>TEMP2: " . ($return_data  -> {'current_observation'}  -> {'condition'}));
//print_r("<p>TEMP3: " . ($return_data  -> {'current_observation'}  -> {'condition'}  -> {'temperature'}));
//print_r("<p>TEMP4: " . ($return_data  -> {'current_observation'}  -> {'condition'}  -> {'temperature'}));







?>
