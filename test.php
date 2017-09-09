<?php

include "./ini.php";

$data = ["name"=>"dadayi","reason"=>"u so stupid"];

\lib\Log::udpLog($data,"testLog", "debug");

echo "try hard,do u best";
