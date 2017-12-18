<?php
include "./ini.php";

echo "<pre/>";
$mongo = new mumongo('127.0.0.1:27017');

print_r($mongo);