<? defined( '_JEXEC' ) or die();

	global $db;
	if($user = get_current_user_z())
	{
		$db->setQuery("DELETE FROM #__z_sessions WHERE user_id={$user->id}")->execute();
		unset($_SESSION['sess_id']);
	}

	$app = JFactory::getApplication();
	$app->redirect(JRoute::_("/login"));


