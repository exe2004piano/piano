<?php

/**
 * Product_rating entry point file for product_rating Component
 * 
 * @package    Product_rating
 * @subpackage com_product_rating
 * @license  !license!
 *
 * Created with Marco's Component Creator for Joomla! 2.5
 * http://www.mmleoni.net/joomla-component-builder
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');


// import joomla controller library
jimport('joomla.application.component.controller');


$ctrl='Product_rating';
$input = JFactory::getApplication()->input;
// Require specific controller if requested
if($controller = $input->getWord('controller'))
{
	$ctrl = $controller;
}
else
{
    // define default view if you need routing...
	//JRequest::setVar( 'view', '***' ); // insert here!!
}

// Get an instance of the required controller
$controller = JControllerLegacy::getInstance($ctrl);
global $url;
$url = $_SERVER['REQUEST_URI'].'?';
$url = substr($url, 0, strpos($url, '?')).'#';
$url = substr($url, 0, strpos($url, '#')).'&';
$url = substr($url, 0, strpos($url, '&')).'=';
$url = trim(str_replace("//", "/", trim(substr($url, 0, strpos($url, '='))).'/'));
$temp = explode("/", $url);

global $manager;
$manager = '';
if( ($temp[3]!='') && ($temp[2]!='') )
{
	$manager = trim($temp[3]);
	include_once __DIR__.'/rating_category.php';
	return;
}

if( $temp[2]!='' )
{
    include_once __DIR__.'/rating_category.php';
    return;
}


// Perform the Request task
$controller->execute($input->getCmd('task'));
// Redirect if set by the controller
$controller->redirect();

?>
