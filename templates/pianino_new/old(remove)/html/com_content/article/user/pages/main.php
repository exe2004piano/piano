<? defined( '_JEXEC' ) or die();

	if(!$user = get_current_user_z())
	{
		$app = JFactory::getApplication();
		$app->redirect(JRoute::_("/login"));
	}
?>


<form class="bv-form bv-form--second" method="post" action="/login?save">
	<div class="bv-form__items">
		<label class="bv-form__item">
			<input required value="<?=$user->name;?>" type="text" class="bv-form__input" placeholder="Ваше имя" name="user_name" value="" />
		</label>

		<label class="bv-form__item">
			<input readonly value="<?=$user->email;?>" type="email" class="bv-form__input" placeholder="Email" name="user_email">
		</label>

		<label class="bv-form__item">
			<input required value="<?=$user->phone;?>" type="tel" class="bv-form__input" placeholder="Телефон" name="user_phone">
		</label>
	</div>

	<div class="bv-form__items">
		<label class="bv-form__item">
			<input value="<?=$user->discont;?>" type="text" class="bv-form__input" placeholder="Номер дисконтной карты" name="user_discont">
		</label>

		<label class="bv-form__item">
			<input value="<?=$user->friendcode;?>" type="text" class="bv-form__input" placeholder="Промокод друга" name="user_friendcode">
		</label>
	</div>

	<div class="bv-form__items">
		<label class="bv-form__item">
			<input value="<?=$user->city;?>" type="text" class="bv-form__input" placeholder="Город" name="user_city">
		</label>

		<label class="bv-form__item">
			<input value="<?=$user->street;?>" type="text" class="bv-form__input" placeholder="Улица" name="user_street">
		</label>
	</div>

	<div class="bv-form__items">
		<label class="bv-form__item">
			<input value="<?=$user->house;?>" type="text" class="bv-form__input" placeholder="Номер дома" name="user_house">
		</label>

		<label class="bv-form__item">
			<input value="<?=$user->birthday;?>" type="text" class="bv-form__input" placeholder="Дата Рождения" name="user_birthday">
		</label>
	</div>

	<div class="bv-form__items">
		<label class="bv-form__item">
			<input type="password" class="bv-form__input" placeholder="Пароль" name="user_pass">
		</label>

		<label class="bv-form__item">
			<input type="password" class="bv-form__input" placeholder="Подтвердите пароль" name="user_pass2">
		</label>
	</div>

	<button type="submit" class="bv-btn bv-btn--third bv-form__submit"><span class="bv-btn__text">Сохранить</span></button>
</form>


