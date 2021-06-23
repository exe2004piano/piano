<?php

include_once("configuration.php");
$c = new JConfig;


$text = "CALLBACK FROM PAGE: " . $_SERVER['HTTP_REFERER'] . " Phone : " . $_GET['phone'];

if($_GET['time_']!="")
    $text .= " Day/time : " . $_GET['time_'];

$text .= "  ROI CODE: " . $_COOKIE['roi_new'];

define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);

if (file_exists(dirname(__FILE__) . '/defines.php')) {
    include_once dirname(__FILE__) . '/defines.php';
}

if (!defined('_JDEFINES')) {
    define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
    require_once JPATH_BASE.'/includes/defines.php';
}

require_once JPATH_BASE.'/includes/framework.php';

$app = JFactory::getApplication('site');
$app->initialise();

if(1*$_COOKIE['roi_new']>0)
{
	$db = JFactory::getDBO();
	$db->setQuery("UPDATE #__z_roistat_new SET z_callback=1, z_callback_text=" . $db->quote($text) . " WHERE id=" . (1*$_COOKIE['roi_new']));
	$db->execute();
}

@mail($c->mails_analog, "callbackhunter", iconv("utf-8", "windows-1251", $text));
echo "1";