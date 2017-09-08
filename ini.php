<?php
mb_internal_encoding("UTF-8");
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set("Asia/Shanghai");

define("WWWROOT", dirname(__FILE__) . "/");
define("PATH_LIB", WWWROOT . "/lib/");
define("PATH_ROBOT", WWWROOT . "/robot/");
define("PATH_DAT", WWWROOT . "data/");

include PATH_LIB . "Log.php";
include PATH_LIB . "Functions.php";
include PATH_LIB . "httpClient.php";
include PATH_LIB . "httpManager.php";
include PATH_ROBOT . "Login.php";
include PATH_ROBOT . "Main.php";
include PATH_ROBOT . "WebSocket.php";