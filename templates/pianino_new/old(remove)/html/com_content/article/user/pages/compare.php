<? defined( '_JEXEC' ) or die();

	if(!$user = get_current_user_z())
	{
		$app = JFactory::getApplication();
		$app->redirect(JRoute::_("/login"));
	}
?>


COMPARE