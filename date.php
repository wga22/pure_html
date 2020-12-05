<?php
$phpVersion = shell_exec('php --version');
echo "Today is " . date("Y/m/d") . "<br>";
echo "Today is " . date("Y.m.d") . "<br>";
echo "Today is " . date("Y-m-d") . "<br>";
echo "Today is " . date("l");
echo "<br>Php version is " . $phpVersion;
echo "<br>cool";
?>
