<? defined( '_JEXEC' ) or die(); ?>


<section class="section">
	<div class="container">
		<div class="section__title">
			<h1>Восстановление пароля</h1>
		</div>

		<div class="layout">
			<div class="layout__item">
				<div class="layout__text">
					<p><strong>Забыли пароль?</strong></p>
					<p>Пожалуйста, введите адрес электронной почты, указанный в параметрах вашей учётной записи. <br> На
						него
						будет отправлен специальный проверочный код. <br> После его получения вы сможете ввести новый пароль
						для
						вашей учётной записи.</p>
				</div>

				<form class="bv-form bv-form--second" novalidate="" method="post" action="/login?remember">
					<div class="bv-form__items">
						<label class="bv-form__item">
							<input type="email" class="bv-form__input" placeholder="Адрес электронной почты" name="user_email" />
						</label>
					</div>

					<button type="submit" class="bv-btn bv-btn--third bv-form__submit"><span class="bv-btn__text">Напомнить пароль</span></button>
				</form>

			</div>
		</div>
	</div>
</section>
