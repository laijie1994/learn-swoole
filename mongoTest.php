<?php
include "./ini.php";

//echo "<pre/>";
$mongo = new mumongo('127.0.0.1:27017');

//print_r($mongo);

var_dump($mongo->selectDataBase("test","test"));

var_dump($mongo->getOne(["name"=>"石老师"]));
