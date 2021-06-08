<? defined( '_JEXEC' ) or die(); ?>


<?
	function z_return($text)
	{
		$_SESSION['z_error'] = "";
		$app = JFactory::getApplication();
		$app->redirect(JRoute::_("/login?register=1&text={$text}"));
	}


	$_SESSION['z_error'] = "";

	$name = trim($_POST['user_name']);
	$email = trim($_POST['user_email']);
	$phone = trim($_POST['user_phone']);
	$pass = trim($_POST['user_pass']);
	$pass2 = trim($_POST['user_pass2']);
	$discont = trim($_POST['user_discont']);
	$friendcode = trim($_POST['user_friendcode']);


	if($name=='')
		z_return("Не введено имя");

	if($email=='')
		z_return("Не введен email");

	if($phone=='')
		z_return("Не введен телефон");

	if($pass!=$pass2)
		z_return("Пароли не совпадают");

	if(get_user_by_email($email))
		z_return("Пользователь с таким email уже существует");

	global $db;

	$z = new stdClass();
	$z->name = $name;
	$z->phone = $phone;
	$z->email = $email;
	$z->pass = generate_pass($pass);
	$z->discont = $discont;
	$z->friendcode = $friendcode;
	$z->active = 1;
	$db->insertObject('#__z_users', $z);
	$id = $db->insertid();

	login_user($email, $pass);
	$app = JFactory::getApplication();
	$app->redirect(JRoute::_("/login"));
?>
