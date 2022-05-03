<?php
/*
title: pull current temperature into format easily read by ESP8266
OUTPUT:  temp: XXXX  
DATE: 2022-05

Notes:
info: other options https://github.com/public-apis/public-apis#weather
change over to https://goweatherforecast.com/current?lat=38.8978&lng=-77.2885&search=&ts=1651595109
<span id="currentTemperature">16<sup>o</sup>C </span></p>

*/

//CONSTANTS
$heroURL = 'https://goweather.herokuapp.com/weather/22182-';
$backupSite= 'https://goweatherforecast.com/current?lat=38.8978&lng=-77.2885&search=&ts=1';

$hTemp = getHeroTemp();
$temp=-100; //try for something better, but -100 implies something wrong
//print_r("hero" . $hTemp . "<p>");
if( $hTemp > -100)
{
    $temp = toFarenheit($hTemp);
}
else    //try a second site
{
    $bkTemp = tryBackupSite();
    if($bkTemp > -100)
    {
        $temp = toFarenheit($bkTemp);
    }
}

print_r("temp:" . $temp);

function toFarenheit($celTemp)
{
    return 9/5*$celTemp+32;
}


function getHeroTemp()
{
    $res = getSite('https://goweather.herokuapp.com/weather/22182');
    $return_data = json_decode($response, true);
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
    $currentTemp=parseToArray($xpath,'currentTemperature');
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

	if (!is_null($elements)) {	
		$resultarray=array();
		foreach ($elements as $element) {
		    $nodes = $element->childNodes;
		    foreach ($nodes as $node) {
		      $resultarray[] = $node->nodeValue;
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
    //print_r("<br>URL:" .strlen($getUrl) . '<br>response:' . strlen($response));
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
