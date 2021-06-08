<? defined( '_JEXEC' ) or die();

	if(!$user = get_current_user_z())
	{
		$app = JFactory::getApplication();
		$app->redirect(JRoute::_("/login"));
	}

	global $db;
	if($info = $db->setQuery("SELECT * FROM #__content WHERE alias='login-info' ")->loadObject())
		echo trim($info->introtext . " " . $info->fulltext);
?>
<div class="clearfix"></div>