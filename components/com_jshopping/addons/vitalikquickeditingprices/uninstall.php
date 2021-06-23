<?php
	defined('_JEXEC') or die;
	
	
	jimport('joomla.filesystem.folder');
	jimport('joomla.filesystem.file');
	JFolder::delete(JPATH_ROOT.'/components/com_jshopping/addons/vitalikquickeditingprices/');
	JFile::delete(JPATH_ROOT.'/administrator/components/com_jshopping/controllers/vitalikquickeditingprices.php');