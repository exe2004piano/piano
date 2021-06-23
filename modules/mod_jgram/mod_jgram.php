<?php
/**
 * JGram Module Entry Point
 * 
 * @package    I got one
 * @subpackage Modules
 * @license    GNU/GPL, see LICENSE.php
 * @link        JGram is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
 
 
// No direct access
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

$doc = JFactory::getDocument();

//add scripts
if($params->get("jquery") === "yes"){
	$doc->addScript("modules/mod_jgram/js/jquery.js");
}

$doc->addScript("modules/mod_jgram/js/feeddisplay.js");


//add styles
$doc->addStyleSheet("modules/mod_jgram/styles/style.css");
	
 
$jgram = modJgramHelper::getJGram($params);
require JModuleHelper::getLayoutPath('mod_jgram');