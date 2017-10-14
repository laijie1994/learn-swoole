<?php

include "./ini.php";

echo "<pre/>";

$db = new Mysqlidb(Array(
    'host' => 'localhost',
    'username' => 'root',
    'password' => 'MyNewPass4!',
    'db' => 'dadayi',
    'prefix' => '',
    'charset' => null));
if (!$db) die("Database error");

var_dump($db);PHP_EOL;

$db->setTrace(true);

die;

$data = ["name" => "dadayi", "reason" => "u so stupid"];

\lib\Log::udpLog($data, "testLog", "debug");

echo "try hard,do u best";
