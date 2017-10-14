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

//var_dump($db);PHP_EOL;

$db->setTrace(true);

if (!$db->ping()) {
    echo "db is not up";
    exit;
}

$data = Array (
    "id" => "",
    "user_id" => 1,
    "content" => 'test this mysql class can use or not',
    "pid" => 1,
    "post_id" => 1,
    "created_at" => $db->now(),
    "updated_at" => $db->now(),
);
$id = $db->insert ('comments', $data);
if($id)
    echo 'user was created. Id=' . $id;

die;

$data = ["name" => "dadayi", "reason" => "u so stupid"];

\lib\Log::udpLog($data, "testLog", "debug");

echo "try hard,do u best";
