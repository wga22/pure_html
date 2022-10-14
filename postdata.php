<?php
/*
title: development utility to posts data to a log file
OUTPUT:  temp: XXXX  
DATE: 2022-05

Notes:
info: other options https://github.com/public-apis/public-apis#weather
change over to https://goweatherforecast.com/current?lat=38.8978&lng=-77.2885&search=&ts=1651595109
<span id="currentTemperature">16<sup>o</sup>C </span></p>

*/

//CONSTANTS

$content = var_export($_POST, true);
$filePath ='/junk/postdata.txt';

print_r("<h2> Development Utility</h2>");
print_r("<h4> Output: {$filePath}</h2>");
print_r("Content: " . $content . "<p>");
file_put_contents($filePath, $content, FILE_APPEND);


print_r("<pre>" . file_get_contents($filePath, false, null) . "</pre>");
?>

<!--
<h1>development utility to posts data to a log file</h>
<scriptxx>
function sendData(path, parameters, method='post') {

  const form = document.createElement('form');
  form.method = method;
  form.action = path;
  document.body.appendChild(form);

  for (const key in parameters) {
      const formField = document.createElement('input');
      formField.type = 'hidden';
      formField.name = key;
      formField.value = parameters[key];

      form.appendChild(formField);
  }
  form.submit();
}
alert("go");
sendData('/fixtemp.php', {query: 'hello world', num: '1'});

</scriptxx>

-->