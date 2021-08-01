<?php
	$zuser = get_current_user_z();
?>

<header class="header">
	<div class="header__top">
		<div class="container">
			<ul class="header__list">
				<li class="header__item">
					<div class="dropdown" data-hover="true" data-toggle-wrap>
						<button class="dropdown__toggle" data-toggle="dropdown">
							<span class="dropdown__text">Кто мы</span>
							<svg class="dropdown-icon" width="10" height="10">
								<use href="/templates/pianino_new/public/images/sprite.svg#dropdown-icon"></use>
							</svg>
						</button>

						<div class="dropdown__menu" data-toggle-content="dropdown">
							<a class="dropdown__link" href="#">Action</a>
							<a class="dropdown__link" href="#">Another action</a>
							<a class="dropdown__link" href="#">Something else here</a>
						</div>
					</div>
				</li>

				<li class="header__item">
					<div class="dropdown" data-hover="true" data-toggle-wrap>
						<button class="dropdown__toggle" data-toggle="dropdown">
							<span class="dropdown__text">Оплата</span>
							<svg class="dropdown-icon" width="10" height="10">
								<use href="/templates/pianino_new/public/images/sprite.svg#dropdown-icon"></use>
							</svg>
						</button>

						<div class="dropdown__menu" data-toggle-content="dropdown">
							<a class="dropdown__link" href="#">Action</a>
							<a class="dropdown__link" href="#">Another action</a>
							<a class="dropdown__link" href="#">Something else here</a>
						</div>
					</div>
				</li>

				<li class="header__item">
					<div class="dropdown" data-hover="true" data-toggle-wrap>
						<button class="dropdown__toggle" data-toggle="dropdown">
							<span class="dropdown__text">Прокат</span>
							<svg class="dropdown-icon" width="10" height="10">
								<use href="/templates/pianino_new/public/images/sprite.svg#dropdown-icon"></use>
							</svg>
						</button>

						<div class="dropdown__menu" data-toggle-content="dropdown">
							<a class="dropdown__link" href="#">Action</a>
							<a class="dropdown__link" href="#">Another action</a>
							<a class="dropdown__link" href="#">Something else here</a>
						</div>
					</div>
				</li>

				<li class="header__item">
					<div class="dropdown" data-hover="true" data-toggle-wrap>
						<button class="dropdown__toggle" data-toggle="dropdown">
							<span class="dropdown__text">BYN</span>
							<svg class="dropdown-icon" width="10" height="10">
								<use href="/templates/pianino_new/public/images/sprite.svg#dropdown-icon"></use>
							</svg>
						</button>

						<div class="dropdown__menu" data-toggle-content="dropdown">
							<a class="dropdown__link" href="#">Action</a>
							<a class="dropdown__link" href="#">Another action</a>
							<a class="dropdown__link" href="#">Something else here</a>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>
	
	<div class="header__body">
		<div class="container">
			<div class="header__body-top">
					<a href="/" class="header__logo logo" title="Piano.by - салон музыкальных инструментов">
						<?php
							$logo = '/templates/pianino_new/public/images/logos/logo-piano.svg';
							$i = rand(1,5);
							if ( file_exists(JPATH_ROOT."/templates/pianino_new/public/images/logos/logo-piano-{$i}.svg") ) {
								$logo = "/templates/pianino_new/public/images/logos/logo-piano-{$i}.svg";
							}
						?>
						<img src="<?=$logo;?>" alt="Piano.by - салон музыкальных инструментов" width="55" height="50"/>
						<img src="/templates/pianino_new/public/images/logos/logo.svg" alt="Piano.by - салон музыкальных инструментов" width="187" height="50">
					</a>

					<nav class="header__nav nav">
						<jdoc:include type="modules" name="top-menu" />
					</nav>
				</ul>
				
				<button class="header__btn btn btn--dark">Заказать обратный звонок</button>

				<div class="header__inf" data-hover="true" data-toggle-wrap>

					<div class="header__inf-block">
						<jdoc:include type="modules" name="top-phones" />
					</div>
				</div>
			</div>

			<div class="header__body-bottom">
				<?php if($addr!=="/") { ?>
					<div class="header__item-btn">
						<a href="#" class="bv-btn bv-btn--toggle " id="list-btn">
							<div class="bv-btn__icon">
								<svg class="menu-icon">
									<use class="menu-icon__part" href="/templates/pianino_new/i/sprite.svg#menu"></use>
								</svg>
							</div>
							<span class="bv-btn__text">Каталог товаров</span>
						</a>

						<jdoc:include type="modules" name="left-menu" />
					</div>
				<?php } ?>

		
				<div class="header__search">
					<form action="/search" method="get" class="form form--search" novalidate>
						<input type="hidden" name="utm_source" value="nash_poisk_piano" />
						<input type="hidden" name="utm_medium" value="cpc" />
						<input type="hidden" name="utm_campaign" value="nash_poisk_test" />

						<input type="text" class="form__input" id="search_input" name="word" placeholder="Например: Пианино Yamaha"
							value="<?php echo isset($_GET['word'])?trim($_GET['word']):""; ?>" autocomplete="off" />
	
						<button class="form__search" type="submit">
							<svg width="18" height="18">
								<use href="/templates/pianino_new/public/images/sprite.svg#search-icon"></use>
							</svg>
						</button>
					</form>
					
					<div id="search_result"></div>
				</div>

				<ul class="header__actions">
					<? if(!$zuser) { ?>
					<li class="header__actions-item" data-hover="true" data-toggle-wrap>
						<a href="javascript:void(0);" class="header__profile" data-toggle='dropdown'>
							<div class="header__action">
								<svg width="46" height="54">
									<use href="/templates/pianino_new/public/images/sprite.svg#profile-icon"></use>
								</svg>
							</div>

							<span class="header__profile-text">Личный кабинет</span>
						</a>

						<div class="header__profile-wrap" data-toggle-content='dropdown'>
							<div class="header__profile-block">
								<a href="/login" class="header__profile-btn btn btn--dark">Ввойти в аккаунт</a>
								<a href="/login?register" class="header__profile-btn btn">Зарегистрироваться</a>
							</div>
						</div>
					</li>
					<? } else { ?>
					<li class="header__actions-item">
						<a href="/login" class="header__profile">
							<div class="header__action">
								<svg width="46" height="54">
									<use href="/templates/pianino_new/public/images/sprite.svg#profile-icon"></use>
								</svg>
							</div>

							<span class="header__profile-text">Личный кабинет</span>
						</a>
					</li>
					<? } ?>

					<li class="header__actions-item">
						<a href="#" class="header__action">
							<svg width="52" height="52">
								<use href="/templates/pianino_new/public/images/sprite.svg#favorite-icon"></use>
							</svg>

							<span class="header__action-val">0</span>
						</a>
					</li>

					<li class="header__actions-item">
						<a href="#" class="header__action">
							<svg width="52" height="52">
								<use href="/templates/pianino_new/public/images/sprite.svg#compare-icon"></use>
							</svg>

							<span class="header__action-val">0</span>
						</a>
					</li>

					<li class="header__actions-item">
						<a href="#" class="header__action">
							<svg width="52" height="52">
								<use href="/templates/pianino_new/public/images/sprite.svg#cart-icon"></use>
							</svg>

							<span class="header__action-val">0</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</header>