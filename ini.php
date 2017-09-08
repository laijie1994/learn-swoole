<?php
mb_internal_encoding("UTF-8");
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set("Asia/Shanghai");

define("WWWROOT", dirname(__FILE__) . "/");

require WWWROOT."vendor/autoload.php";

define("PATH_LIB", WWWROOT . "/lib/");
define("PATH_ROBOT", WWWROOT . "/robot/");
define("PATH_DAT", WWWROOT . "data/");
