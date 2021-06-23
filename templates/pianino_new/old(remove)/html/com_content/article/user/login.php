<? defined( '_JEXEC' ) or die();

	if($user = get_current_user_z())
	{
		include_once __DIR__.'/user_cabinet.php';
		return;
	}
?>


<section class="section">
	<div class="container">
		<div class="section__title">
			<h1>Авторизация пользователя</h1>
		</div>

		<div class="layout">
			<div class="layout__item">
				<div class="layout__title">
					<h2>Авторизация</h2>
				</div>

				<form class="bv-form bv-form--second" action="/login?auth" method="post" >
					<div class="bv-form__items">
						<label class="bv-form__item">
							<input required type="text" class="bv-form__input" placeholder="Логин (Email)" name="user_login" />
						</label>
					</div>

					<div class="bv-form__items">
						<label class="bv-form__item">
							<input required type="password" class="bv-form__input" placeholder="Пароль" name="user_pass" />
						</label>
					</div>

					<button type="submit" class="bv-btn bv-btn--third bv-form__submit"><span class="bv-btn__text">Войти</span></button>
					<a href="/login?remind" class="bv-btn bv-btn--fourth" style="margin: 0 0 0 15px;">Забыли пароль</a>
				</form>

			</div>
		</div>
	</div>
</section>

