<? defined( '_JEXEC' ) or die();

	$login = trim($_POST['user_login']);
	$pass = trim($_POST['user_pass']);

	login_user($login, $pass);
	$app = JFactory::getApplication();
	$app->redirect(JRoute::_("/login"));

