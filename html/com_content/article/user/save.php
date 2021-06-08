<? defined( '_JEXEC' ) or die();

	if(!$user = get_current_user_z())
	{
		$app = JFactory::getApplication();
		$app->redirect(JRoute::_("/login"));
	}

	function z_return($text)
	{
		$_SESSION['z_save_error'] = $text;
		$app = JFactory::getApplication();
		$app->redirect(JRoute::_("/login"));
	}


	$name = trim($_POST['user_name']);
	$phone = trim($_POST['user_phone']);
	$pass = trim($_POST['user_pass']);
	$pass2 = trim($_POST['user_pass2']);
	$discont = trim($_POST['user_discont']);
	$friendcode = trim($_POST['user_friendcode']);

	$city = trim($_POST['user_city']);
	$street = trim($_POST['user_street']);
	$house = trim($_POST['user_house']);
	$birthday = trim($_POST['user_birthday']);

	$_SESSION['z_save_error'] = "";

	if($name=='')
		z_return("Не введено имя");

	if($phone=='')
		z_return("Не введен телефон");

	if($pass!=$pass2)
		z_return("Пароли не совпадают");


	global $db;

	$z = new stdClass();
	$z->id = $user->id;
	$z->name = $name;
	$z->phone = $phone;
	$z->pass = generate_pass($pass);
	$z->discont = $discont;
	$z->friendcode = $friendcode;
	$z->city = $city;
	$z->street = $street;
	$z->house = $house;
	$z->birthday = $birthday;

	if($pass!='')
		$z->pass = generate_pass($pass);

	$db->updateObject('#__z_users', $z, "id");

	$app = JFactory::getApplication();
	$app->redirect(JRoute::_("/login"));
?>