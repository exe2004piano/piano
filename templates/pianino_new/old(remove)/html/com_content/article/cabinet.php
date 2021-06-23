<?php defined( '_JEXEC' ) or die();

	//ini_set('error_reporting', E_ALL);
	//ini_set('display_errors', 1);
	//ini_set('display_startup_errors', 1);

?>
	<style>
		.error-text {color: red!important; margin-bottom: 15px!important;}
	</style>
<?


	include_once __DIR__.'/user/f.php';

	if(sizeof($_GET)==0)
	{
		include_once __DIR__ . '/user/login.php';
		return;
	}

	$f = trim(array_key_first($_GET));

	if(file_exists(__DIR__.'/user/'.$f.'.php'))
		include_once __DIR__.'/user/'.$f.'.php';
	else
		include_once __DIR__ . '/user/login.php';

	return;