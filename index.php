<?php


global $db_time;
$db_time = 0;
/*
if(strpos(" ".$_SERVER['HTTP_HOST'], 'piano.by')<1)
{
	echo "SSL NOT FOUND";
	die;
}
*/

$time__ = time();

global $roicode_products;
$roicode_products = Array();
global $z_country;
$z_country = "";


if( (!isset($_COOKIE['z_country'])) || (trim($_COOKIE['z_country'])=='') )
{
    if(isset($_SERVER['HTTP_USER_AGENT']))
    {
        $is_bot = preg_match(
            "~(Google|Yahoo|Rambler|Bot|Yandex|Spider|Snoopy|Crawler|Finder|Mail|curl)~i",
            $_SERVER['HTTP_USER_AGENT']
        );
        if(!$is_bot)
        {
            // --- подключим сайпекс-базу для определения страны по IP
            include($_SERVER['DOCUMENT_ROOT'] . "/SxGeo.php");
            $SxGeo = new SxGeo($_SERVER['DOCUMENT_ROOT'] . '/SxGeo.dat');
            $ip = $_SERVER['REMOTE_ADDR'];
            $z_country = trim($SxGeo->get($ip));
			
		
            if ($z_country == 'RU')
                setcookie('currency', '3', null, '/');

            setcookie('z_country', $z_country, null, '/');
            unset($SxGeo);
        }
    }
}
else
{
    $z_country = $_COOKIE['z_country'];
}

$z_country = trim($z_country);
// --- запрет на доступ из США, Тайланда, Японии и пр. - (парсят гады)
/*
if( ($z_country=='US') || ($z_country=='TH') || ($z_country=='JP') || ($z_country=='CA') )
{
	$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/log/block.txt', "a");
	fwrite($fp, $z_country.": ".serialize($_SERVER)."\r\n\r\n");
	fclose($fp);

	die;
}
*/

/**
 * @package    Joomla.Site
 *
 * @copyright  Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Define the application's minimum supported PHP version as a constant so it can be referenced within the application.
 */
define('JOOMLA_MINIMUM_PHP', '5.3.10');

if (version_compare(PHP_VERSION, JOOMLA_MINIMUM_PHP, '<'))
{
    die('Your host needs to use PHP ' . JOOMLA_MINIMUM_PHP . ' or higher to run this version of Joomla!');
}

// Saves the start time and memory usage.
$startTime = microtime(1);
$startMem  = memory_get_usage();

/**
 * Constant that is checked in included files to prevent direct access.
 * define() is used in the installation folder rather than "const" to not error for PHP 5.2 and lower
 */
define('_JEXEC', 1);


if (file_exists(__DIR__ . '/defines.php'))
{
    include_once __DIR__ . '/defines.php';
}

if (!defined('_JDEFINES'))
{
    define('JPATH_BASE', __DIR__);
    require_once JPATH_BASE . '/includes/defines.php';
}

require_once JPATH_BASE . '/includes/framework.php';

// Set profiler start time and memory usage and mark afterLoad in the profiler.
JDEBUG ? $_PROFILER->setStart($startTime, $startMem)->mark('afterLoad') : null;

// Instantiate the application.
$app = JFactory::getApplication('site');

// Execute the application.
error_reporting(E_ERROR);
ini_set('display_errors',0);


// ob_start();
$app->execute();
// $main_text = ob_get_clean();
// ob_end_clean();
// $main_text = main_text_replace($main_text);
// echo $main_text;

echo "<!--" . ((memory_get_usage() - $startMem)/1024/1024) ."mb<br />time=" . (microtime(1)-$startTime) . " -->";
echo "<!--DB time: ".$db_time." -->";
