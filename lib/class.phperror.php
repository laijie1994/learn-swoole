<?php
function shutdown(){ //捕获错误
	$aError = error_get_last();
	if ((!empty( $aError)) && ($aError['type'] !== E_NOTICE)) {
		$date = date( 'Ymd');
		$file = PATH_DAT . 'phperror/' . $date . '.php';
		$error = '';
		if ( ! file_exists( $file)) {
			$error = "<?php\nexit();\n";
		}
		if ( ! is_dir( PATH_DAT . 'phperror')) {
			mkdir( PATH_DAT . 'phperror',  0777);
		}
		$error .= date( 'Y-m-d H:i:s') . '---';
		$error .= 'Error:' . $aError['message'] . '--';
		$error .= 'File:' . $aError['file'] . '--';
	    $error .= 'Line:' . $aError['line']. '--';
 		if (isset($_REQUEST['api'])) {
 		    $string = json_decode($_REQUEST['api'],true);
 			$error .= 'Method:' . $string['method']. '--';
 			$error .= 'Param:' . json_encode($string['game_param']). '--';
 			$sesskey = explode('-', $string['sesskey']);
 			$error .= 'Mid:' . (int)$sesskey[0];
 		}
		@file_put_contents( $file, $error . " \n ", FILE_APPEND | LOCK_EX);
		exit();
	}
}
function myErrorHandler($errno, $errstr, $errfile, $errline){
	if ($errno != E_NOTICE) {
		$date = date( 'Ymd');
		$file = PATH_DAT . '/phperror/' . $date . '.php';
		$error = '';
		if ( ! file_exists( $file)) {
			$error = "<?php\nexit();\n";
		}
		if ( ! is_dir( PATH_DAT . 'phperror')) {
			mkdir( PATH_DAT . 'phperror',  0777);
		}
		$error .= date( 'Y-m-d H:i:s') . '---';
		$error .= 'Error:' . $errstr . '--';
		$error .= 'File:' . $errfile . '--';
		$error .= 'Line:' . $errline. '--';
 	    if (isset($_REQUEST['api'])) {
 		    $string = json_decode($_REQUEST['api'],true);
 			$error .= 'Method:' . $string['method']. '--';
 			$error .= 'Param:' . json_encode($string['game_param']). '--';
 			$sesskey = explode('-', $string['sesskey']);
 			$error .= 'Mid:' . (int)$sesskey[0];
 		}
		$file = PATH_DAT . 'phperror/' . $date . '.php';
		@file_put_contents( $file, $error . " \n ", FILE_APPEND | LOCK_EX);
	}
}
function myExceptionHandler($e){
	$class = get_class($e);
	$message = $e->getMessage();
	$file = $e->getFile();
	$line = $e->getLine();
	$tarce = $e->getTrace();
	$str = date('Y-m-d H:i:s').PHP_EOL;
	$str .= "PHP Fatal error:  Uncaught exception '{$class}' with message '{$message}' in {$file}:{$line}".PHP_EOL;
	foreach ($tarce as $k => $v){
		$str .= "#{$k} {$v['file']}({$v['line']}):{$v['class']}{$v['type']}{$v['function']}(";
		foreach ($v['args'] as $key => $value){
			$arg = str_replace(array(PHP_EOL,'\\',' '),"",var_export($v,true));
			$str .= $arg.",";
		}
		$str = trim($str,",");
		$str .= ")".PHP_EOL;
	}
	$date = date( 'Ymd');
	$file = PATH_DAT . 'phperror/' . $date . '.php';
	@file_put_contents( $file, $str . " \n ", FILE_APPEND | LOCK_EX);
}
set_exception_handler('myExceptionHandler'); //异常捕获自定义处理函数注册
//注册错误函数
set_error_handler( 'myErrorHandler');
register_shutdown_function( 'shutdown');

