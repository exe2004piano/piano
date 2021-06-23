<?php
/**
 * @version		1.0.4 from Arkadiy Sedelnikov
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later;
 */

// No direct access to this file
defined('_JEXEC') or die;

// import joomla controller library
jimport('joomla.application.component.controller');

$controller = JControllerLegacy::getInstance('Loginza');

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();