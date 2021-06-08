<? defined( '_JEXEC' ) or die();

	if(!$user = get_current_user_z())
	{
		$app = JFactory::getApplication();
		$app->redirect(JRoute::_("/login"));
	}

	if(sizeof($_GET)==0)
		$page = '';
	else
		$page = trim(array_key_first($_GET));

	if(!file_exists(__DIR__.'/pages/'.$page.'.php'))
		$page = 'main';
?>

<section class="section">
	<div class="container">
		<div class="section__title">
			<h1>Мой кабинет</h1>
		</div>

		<div class="layout">
			<aside class="layout__item layout__item-aside">
				<ul class="layout-list">
					<li class="layout-list__item"><a class="layout-list__link <? if($page=='main') echo ' is--active ';?>" href="/login">Мой кабинет</a></li>
					<li class="layout-list__item"><a class="layout-list__link <? if($page=='orders') echo ' is--active ';?>" href="/login?orders">Мои Заказы</a></li>
					<li class="layout-list__item"><a class="layout-list__link <? if($page=='wishlist') echo ' is--active ';?>" href="/login?wishlist">Избранные товары</a></li>
                    <li class="layout-list__item"><a class="layout-list__link <? if($page=='info') echo ' is--active ';?>" href="/login?info">Информация о скидках</a></li>
					<?/*<li class="layout-list__item"><a class="layout-list__link <? if($page=='compare') echo ' is--active ';?>" href="/login?compare">Сравнение</a></li>*/?>
					<?/*<li class="layout-list__item"><a class="layout-list__link" href="#">О нас</a></li>*/?>
				</ul>
			</aside>

			<div class="layout__item">
				<?
					include_once __DIR__.'/pages/'.$page.'.php';
				?>
                <a href="/login?logout" class="bv-btn bv-btn--fourth">Выйти</a>
			</div>

		</div>
	</div>
</section>


