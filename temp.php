<?php
/*
title: pull current temperature into format easily read by ESP8266
OUTPUT:  temp: XXXX  
DATE: 2022-05

Notes:
info: other options https://github.com/public-apis/public-apis#weather
change over to https://goweatherforecast.com/current?lat=38.8978&lng=-77.2885&search=&ts=1651595109
<span id="currentTemperature">16<sup>o</sup>C </span></p>

--TODO:
1) track downtime of the 2 sites

*/
include 'vars.php';

//CONSTANTS not able to set globally?!
//$heroURL = 'https://goweather.herokuapp.com/weather/22182';
//$backupSite= 'https://goweatherforecast.com/current?lat=38.8978&lng=-77.2885&search=&ts=1';

//$hTemp = getHeroTemp(); //BROKEN for now
$hTemp = getOpenWeatherMap($LAT, $LNG, $weather_api);
$temp=-100; //try for something better, but -100 implies something wrong
//print_r("hero" . $weather_api . "<p>");
if( $hTemp > -100)
{
    $temp = KtoFarenheit($hTemp);
}
else    //try a second site
{
    $bkTemp = tryBackupSite();
    if($bkTemp > -100)
    {
        $temp = CtoFarenheit($bkTemp);
    }
}

//print_r("api:" . $api);
print_r("temp:" . $temp);


function KtoFarenheit($kTemp)
{
//(K − 273.15) × 9/5 + 32 = °F
    return CtoFarenheit($kTemp-273.15);
}


function CtoFarenheit($celTemp)
{
    return 9/5*$celTemp+32;
}


function getOpenWeatherMap($lat, $lng, $api)
{
    $surl = 'https://api.openweathermap.org/data/2.5/weather?lat='.$lat.'&lon='.$lng.'&appid='.$api;
    //print_r($surl);
    $res = getSite($surl);
//TODO: test for valid JSON response elements
//print_r($res . is_null($res));
    $return_data = json_decode($res, true);
//print_r($return_data . "<p>");
    $temp = floatval($return_data['main']['temp']);
//print_r($temp . " weather api<p>");
    if(strlen($temp)==0 || is_null($temp))
    {
        //print_r("<p>cannot find hero: " . $temp);
        $temp=-100;
    }
    return $temp;
}

function getHeroTemp()
{
    $res = getSite('https://goweather.herokuapp.com/weather/22182');
//print_r($res . is_null($res));
    $return_data = json_decode($res, true);
//print_r($return_data . "<p>");
    $temp = floatval($return_data['temperature']);
    if(strlen($return_data['temperature'])==0 || is_null($temp))
    {
        //print_r("<p>cannot find hero: " . $temp);
        $temp=-100;
    }
    return $temp;
}


function tryBackupSite()
{
    //<span id="currentTemperature">16<sup>o</sup>C </span></p>
    $dom = new DomDocument(); 
    $dom->loadHTML(getSite('https://goweatherforecast.com/current?lat=38.8978&lng=-77.2885&search=&ts=1'));   
    $xpath = new DOMXpath($dom);
//BROKEN print_r($xpath . "zzzzz<p>");
    $currentTemp=parseToArray($xpath,'currentTemperature');
    $temp = -100;
    if(!is_null($currentTemp) && count($currentTemp)>0)
    {
        $temp=floatval($currentTemp[0]);
    }
    return $temp;
}

function parseToArray($xpath,$id)
{
	$xpathquery="//span[@id='".$id."']";
	$elements = $xpath->query($xpathquery);
//print_r($elements->length . " elements in the dom list<br>");
	if (!is_null($elements)) {	
		$resultarray=array();
		foreach ($elements as $element) {
		    $nodes = $element->childNodes;
		    foreach ($nodes as $node) {
		      $resultarray[] = $node->nodeValue;
			print_r($node . "+++<br>");
		    }
		}
		return $resultarray;
	}
}

function getSite($getUrl)
{
    //$getUrl = 'https://goweather.herokuapp.com/weather/22182';
    $options = array(
        CURLOPT_HEADER => false,
        CURLOPT_URL => $getUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false
    );

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    curl_close($ch);
//    print_r("<br>URL:" .strlen($getUrl) . '<br>response length:' . strlen($response));
//    print_r("<pre>content" .$response . "</pre>");
    return $response;
}


/*
JUNK
var_dump($heading);
echo "<br/>";
var_dump($content);
echo "<br/>";
*/

?>
