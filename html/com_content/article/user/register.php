<? defined( '_JEXEC' ) or die(); ?>

<section class="section">
	<div class="container">
		<div class="section__title">
			<h1>Регистрация пользователя</h1>
		</div>

		<div class="layout">
			<div class="layout__item">
				<div class="layout__title">
					<h2>Зарегестрироваться</h2>
				</div>

				<? if(isset($_SESSION['z_error'])) { ?>
					<h4 class="error-text">
						<?=$_SESSION['z_error']; ?>
					</h4>
				<? } ?>

				<form class="bv-form bv-form--second" method="post" action="/login?new_user">

					<div class="bv-form__items">
						<label class="bv-form__item">
							<input type="text" class="bv-form__input" placeholder="Ваше имя *" name="user_name" required>
						</label>

						<label class="bv-form__item">
							<input type="email" class="bv-form__input" placeholder="Email (логин) *" name="user_email" required>
						</label>

						<label class="bv-form__item">
							<input type="tel" class="bv-form__input" placeholder="Телефон *" name="user_phone" required>
						</label>
					</div>

					<div class="bv-form__items">
						<label class="bv-form__item">
							<input type="text" class="bv-form__input" placeholder="Номер дисконтной карты" name="user_discont">
						</label>

						<label class="bv-form__item">
							<input type="text" class="bv-form__input" placeholder="Промокод друга" name="user_friendcode">
						</label>
					</div>

					<div class="bv-form__items">
						<label class="bv-form__item">
							<input type="password" class="bv-form__input" placeholder="Пароль *" name="user_pass" required>
						</label>

						<label class="bv-form__item">
							<input type="password" class="bv-form__input" placeholder="Подтвердите пароль *" name="user_pass2" required>
						</label>
					</div>

					<button type="submit" class="bv-btn bv-btn--third bv-form__submit"><span class="bv-btn__text">Регистрация</span></button>
				</form>
			</div>
		</div>
	</div>
</section>