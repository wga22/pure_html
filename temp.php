<?php
// Copyright 2019 Oath Inc. Licensed under the terms of the zLib license see https://opensource.org/licenses/Zlib for terms.

function buildBaseString($baseURI, $method, $params) {
    $r = array();
    ksort($params);
    foreach($params as $key => $value) {
        $r[] = "$key=" . rawurlencode($value);
    }
    return $method . "&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
}

function buildAuthorizationHeader($oauth) {
    $r = 'Authorization: OAuth ';
    $values = array();
    foreach($oauth as $key=>$value) {
        $values[] = "$key=\"" . rawurlencode($value) . "\"";
    }
    $r .= implode(', ', $values);
    return $r;
}

$url = 'https://weather-ydn-yql.media.yahoo.com/forecastrss';
$app_id = '3FoETUVr';
$consumer_key = 'dj0yJmk9bEJDanVGSjQ3U2Z4JnM9Y29uc3VtZXJzZWNyZXQmc3Y9MCZ4PThj';
$consumer_secret = '974b9f8d7a143508d4b9bb2720eaadfc1b9d83e6';

$query = array(
    'woeid' => '12766846',
    'format' => 'json',
);

$oauth = array(
    'oauth_consumer_key' => $consumer_key,
    'oauth_nonce' => uniqid(mt_rand(1, 1000)),
    'oauth_signature_method' => 'HMAC-SHA1',
    'oauth_timestamp' => time(),
    'oauth_version' => '1.0'
);

$base_info = buildBaseString($url, 'GET', array_merge($query, $oauth));
$composite_key = rawurlencode($consumer_secret) . '&';
$oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
$oauth['oauth_signature'] = $oauth_signature;

$header = array(
    buildAuthorizationHeader($oauth),
    'X-Yahoo-App-Id: ' . $app_id
);
$options = array(
    CURLOPT_HTTPHEADER => $header,
    CURLOPT_HEADER => false,
    CURLOPT_URL => $url . '?' . http_build_query($query),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false
);

$ch = curl_init();
curl_setopt_array($ch, $options);
$response = curl_exec($ch);
curl_close($ch);

//print_r('<p>this is the raw' . $response);
$return_data = json_decode($response, true);
//print_r('<p>this is the raw ' . $return_data['current_observation']);
//print_r('<p>this is the raw ' . $return_data['current_observation']['condition']['temperature']);
print_r("temp:" . $return_data['current_observation']['condition']['temperature']);


//print_r('<p>this is the processed' .$return_data);
//print_r("<p>TEMP1: " . ($return_data  -> {'current_observation'} ));
//print_r("<p>TEMP2: " . ($return_data  -> {'current_observation'}  -> {'condition'}));
//print_r("<p>TEMP3: " . ($return_data  -> {'current_observation'}  -> {'condition'}  -> {'temperature'}));
//print_r("<p>TEMP4: " . ($return_data  -> {'current_observation'}  -> {'condition'}  -> {'temperature'}));







