<?php
	$zuser = get_current_user_z();
?>
<header class="header" id="header">
	<div class="overlay" id="bg"></div>
	<div class="header__top">
		<div class="container">
			<ul class="header__top-list">
                <? if(!$zuser) { ?>
				<li class="header__top-item">
					<a href="/login" class="header__top-link">Вход</a>
				</li>
				<li class="header__top-item">
					<a href="/login?register" class="header__top-link">Регистрация</a>
				</li>
                <? } else { ?>
                    <li class="header__top-item">
                        <a href="/login" class="header__top-link">Мой кабинет</a>
                    </li>
                <? } ?>

			</ul>
		</div>
	</div>

	<div class="header__middle">

		<div class="container">
			<ul class="header__items">

				<li class="header__item header__item-logo">

					<?php
						$logo = '/templates/pianino_new/i/logo-piano.svg';
						for($i=1; $i<10;$i++)
						{
							if( (rand(0,100)>50) && (file_exists(JPATH_ROOT."/templates/pianino_new/i/logo-piano-{$i}.svg")) )
							{
								$logo = "/templates/pianino_new/i/logo-piano-{$i}.svg";
								break;
							}
						}
					?>
					<a href="/" class="main-logo" title="Piano.by - салон музыкальных инструментов">
						<div class="main-logo__part1">
							<img src="<?=$logo;?>" alt="Piano.by - салон музыкальных инструментов" />
						</div>
						<div class="main-logo__part2">
							<img src="/templates/pianino_new/i/logo.svg" alt="Piano.by - салон музыкальных инструментов" />
						</div>
					</a>

				</li>


				<li class="header__item header__item-nav" id="navigation">
					<nav class="nav">
						<jdoc:include type="modules" name="top-menu" />
					</nav>

					<div class="links is--hide">
						<? if(!$zuser) { ?>
							<a href="/login" class="links__link">Вход</a>
							<a href="/login?register" class="links__link">Регистрация</a>
						<? } else { ?>	
							<a href="/login" class="links__link">Мой кабинет</a>
						<? } ?>

					</div>
	
	
					<div class="bv-btn-wrap is--hide">
						<a href="#" class="bv-btn bv-btn--second">
							<span class="bv-btn__text">Валюта BYN</span>
						</a>
					</div>
				</li>

				<li class="header__item header__item-mobile">
					<ul class="mobile-items" id="mobile-items">
						<li class="mobile__item mobile__item-search">
							<a href="#" class="icon" id="mobile-search">
								<svg class="search-icon">
									<use class="search-icon__part" href="/templates/pianino_new/i/sprite.svg#search"></use>
								</svg>
							</a>
						</li>
						<li class="mobile__item mobile__item-сompare">
							<a href="/compare" class="icon" data-num="0" id="compare_mobile">
								<svg class="libra-icon">
									<use class="libra-icon__part" href="/templates/pianino_new/i/sprite.svg#libra"></use>
								</svg>
								<span class="icon__block" id="compare_span_mobile">0</span>
							</a>
						</li>

						<li class="mobile__item" id="row-like-mob">
							<a href="/like-items" class="icon" data-num="0" id="like-mobile">
								<svg class="heart-icon">
									<use class="heart-icon__part" href="/templates/pianino_new/i/sprite.svg#heart"></use>
								</svg>
								<span class="icon__block" id="like_span_mobile">0</span>
							</a>
						</li>

						<li class="mobile__item">
							<a href="/basket" class="cart icon">
								<div class="cart__item cart__item-icon">
									<svg class="cart-icon">
										<use class="cart-icon__part" href="/templates/pianino_new/i/sprite.svg#cart"></use>
									</svg>
								</div>

								<div class="cart__item">
									<span id="cart_span_mobile" class="icon__block"></span>
								</div>
							</a>
						</li>
					</ul>
				</li>

				<li class="header__item header__item-block">
					<jdoc:include type="modules" name="top-phones" />
				</li>

				<li class="header__item header__item-sandwich">
					<div class="sandwich" id="sandwich">
						<span class="sandwich__part sandwich__part--top"></span>
						<span class="sandwich__part sandwich__part--middle"></span>
						<span class="sandwich__part sandwich__part--bottom"></span>
					</div>
				</li>
			</ul>
		</div>
	</div>

	<div class="header__bottom" id="mobile-search-body">
		<div class="container">
			<ul class="header__items">
				<li class="header__item header__item-list b-menu">
					<div class="header__item-btn">
						<a href="#" class="bv-btn bv-btn--toggle <?php if($addr=="/") echo 'js-index'; ?>" id="list-btn">
							<div class="bv-btn__icon">
								<svg class="menu-icon">
									<use class="menu-icon__part" href="/templates/pianino_new/i/sprite.svg#menu"></use>
								</svg>
							</div>
							<span class="bv-btn__text">Каталог товаров</span>
						</a>
					</div>

					<jdoc:include type="modules" name="left-menu" />
				</li>

				<li class="header__item header__item-search">
					<form id="search_form" action="/search" method="get" class="bv-form">

                        <input type="hidden" name="utm_source" value="nash_poisk_piano" />
                        <input type="hidden" name="utm_medium" value="cpc" />
                        <input type="hidden" name="utm_campaign" value="nash_poisk_test" />

                        <div class="bv-form__row">

							<a href="#" id="mic" title="Нажмите для голосового поиска" class="bv-btn bv-btn--voice">
								<svg class="voice-icon">
									<use class="voice-icon__part" href="/templates/pianino_new/i/sprite.svg#voice"></use>
								</svg>
							</a>
							<div class="bv-form__wrap">
								<input type="text" class="bv-form__input" id="search_words" name="word" placeholder="Поиск по сайту"
									   value="<?php echo isset($_GET['word'])?trim($_GET['word']):""; ?>" autocomplete="off" />

								<button class="bv-btn bv-btn--search bv-form__submit" type="submit">
									<svg class="search-icon">
										<use class="search-icon__part" href="/templates/pianino_new/i/sprite.svg#search"></use>
									</svg>
								</button>

								<div class="bv-form__popup" id="search_results"></div>
							</div>

							<div class="bv-form__blocks" id="prompt">
								<div class="bv-form__block">
									<span>Например:</span>
								</div>

								<div class="bv-form__block">
									<a href="#" class="bv-form__btn"
									   onclick="$('#search_results').html(''); $('#search_words').val('').stop().trigger('keyup').val('Синтезатор Casio').trigger('keyup').trigger('focus'); return false;">Синтезатор
										Casio</a>
									<a href="#" class="bv-form__btn"
									   onclick="$('#search_results').html(''); $('#search_words').val('').stop().trigger('keyup').val('Пианино Yamaha').trigger('keyup').trigger('focus'); return false;">Пианино
										Yamaha</a>
								</div>
							</div>
						</div>
					</form>
				</li>

				<li class="header__item header__item-compare">
					<a href="/compare" class="icon" data-num="0" id="compare_a">
						<svg class="libra-icon">
							<use class="libra-icon__part" href="/templates/pianino_new/i/sprite.svg#libra"></use>
						</svg>
						<span class="icon__block" id="compare_span">0</span>
					</a>
					
					<div class="inf-popin">
						<div class="inf-popin__block">
							<p class="inf-popin__title">Cравнение</p>

							<div class="inf-popin__contains">
								<p class="inf-popin-text">Нет товаров в сравнении</p>
							</div>

							<a href="/compare" class="inf-popin-link">перейти к сравнению</a>
						</div>
					</div>
				</li>

				<li class="header__item header__item-favorite">
					<a href="#" class="icon" data-num="1" id="like_a">
						<svg class="heart-icon">
							<use class="heart-icon__part" href="/templates/pianino_new/i/sprite.svg#heart"></use>
						</svg>
						<span class="icon__block" id="like_span">0</span>
					</a>

					<div class="inf-popin">
						<div class="inf-popin__block">
							<p class="inf-popin__title">Избранное</p>

							<div class="inf-popin__contains">
								<p class="inf-popin-text">Нет товаров в избранном</p>
							</div>

							<a href="/like-items" class="inf-popin-link">перейти в избранное</a>
						</div>
					</div>
				</li>

				<li class="header__item header__item-cart">
					<a href="/basket" class="cart icon">
						<div class="cart__item cart__item-icon">
							<svg class="cart-icon">
								<use class="cart-icon__part" href="/templates/pianino_new/i/sprite.svg#cart"></use>
							</svg>
							<span class="icon__block" id="cart_val_desk">0</span>
						</div>
					</a>

					<div class="inf-popin">
						<div class="inf-popin__block">
							<p class="inf-popin__title">Корзина</p>

							<div class="inf-popin__contains">
								<!-- <p class="inf-popin-text">Нет товаров в корзине</p> -->

								<ul class="inf-popin__list">
									<li class="inf-popin__item">
										<a href="#" class="inf-popin__card">
											<img src="/images/cache/flight-nus-310-szopran-ukulele-tokkal-jpg_278_215_90_1.jpg" alt="" class="inf-popin__img" width="96" height="96">
											<div class="inf-popin__inf">
												<p class="inf-popin__small">Цифровые пианино</p>
												<p class="inf-popin__medium">Casio CDP-S100</p>
												<p class="inf-popin__label">34 490 $</p>
											</div>
										</a>

										<button class="inf-popin__remove"></button>
									</li>

									<li class="inf-popin__item">
										<a href="#" class="inf-popin__card">
											<img src="/images/cache/flight-nus-310-szopran-ukulele-tokkal-jpg_278_215_90_1.jpg" alt="" class="inf-popin__img" width="96" height="96">
											<div class="inf-popin__inf">
												<p class="inf-popin__small">Цифровые пианино</p>
												<p class="inf-popin__medium">Casio CDP-S100</p>
												<p class="inf-popin__label">34 490 $</p>
											</div>
										</a>

										<button class="inf-popin__remove"></button>
									</li>

									<li class="inf-popin__item">
										<a href="#" class="inf-popin__card">
											<img src="/images/cache/flight-nus-310-szopran-ukulele-tokkal-jpg_278_215_90_1.jpg" alt="" class="inf-popin__img" width="96" height="96">
											<div class="inf-popin__inf">
												<p class="inf-popin__small">Цифровые пианино</p>
												<p class="inf-popin__medium">Casio CDP-S100</p>
												<p class="inf-popin__label">34 490 $</p>
											</div>
										</a>

										<button class="inf-popin__remove"></button>
									</li>
								</ul>
							</div>

							<a href="/basket" class="inf-popin-link">перейти в корзину</a>
						</div>
					</div>

					<div class="cart-list" id="basket_div">
						<jdoc:include type="modules" name="top-basket" />
					</div>
				</li>
			</ul>
		</div>
	</div>
</header>