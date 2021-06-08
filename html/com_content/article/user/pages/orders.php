<? defined( '_JEXEC' ) or die();
    global $db;
	if(!$user = get_current_user_z())
	{
		$app = JFactory::getApplication();
		$app->redirect(JRoute::_("/login"));
	}


	// --- нужно запросить данные о пользователе из CRM отправив его контакты и прочее:
	if($info = get_user_info_crm($user))
    {
        if( ($user->crm_id==0) && ($info->crm_id>0) )   // --- первый раз обратился к базе за списком заказов
        {
			$crm_id = (int)$info->crm_id;
			$q = "UPDATE #__z_users SET crm_id={$crm_id} WHERE id={$user->id}";
            $db->setQuery($q)->execute();
        }

        if( (isset($info->orders)) && (sizeof($info->orders)>0) )
        {
            foreach ($info->orders AS $id=>$ord)
			{
				?>
				<div class="layout-row layout-row--second">
					<div class="layout-row__items">
						<div class="layout-row__item">
							<p><span>Заказ №</span> <strong><?=$id;?></strong></p>
						</div>

						<div class="layout-row__item">
							<p><span>от</span> <strong><?=date("d.m.Y", $ord->date);?></strong></p>
						</div>

						<div class="layout-row__item">
							<p><span>товаров</span> <strong><?=$ord->num;?></strong></p>
						</div>

						<div class="layout-row__item">
							<p><span>На сумму</span> <strong><?=$ord->summ;?> руб.</strong></p>
						</div>

						<div class="layout-row__item">
							<p><span>Статус:</span> <strong><?=$ord->status;?></strong></p>
						</div>
					</div>

					<div class="layout-row__link">
						<a href="/login?order&order_id=<?=$id;?>" class="bv-btn bv-btn--third">Подробности</a>
					</div>

				</div>
				<?
			}
        }





		if( ($user->is_parent == 1) && (isset($info->teacher_orders)) && (sizeof($info->teacher_orders)>0) )
		{
		    echo "<hr /><b style='color: red;'>Заказы по персональному промокоду:</b>";
			foreach ($info->teacher_orders AS $id=>$ord)
			{
				?>
                <div class="layout-row layout-row--second">
                    <div class="layout-row__items">
                        <div class="layout-row__item">
                            <p><span>Заказ №</span> <strong><?=$id;?></strong></p>
                        </div>

                        <div class="layout-row__item">
                            <p><span>от</span> <strong><?=date("d.m.Y", $ord->date);?></strong></p>
                        </div>

                        <div class="layout-row__item">
                            <p><span>товаров</span> <strong><?=$ord->num;?></strong></p>
                        </div>

                        <div class="layout-row__item">
                            <p><span>На сумму</span> <strong><?=$ord->summ;?> руб.</strong></p>
                        </div>

                        <div class="layout-row__item">
                            <p><span>Статус:</span> <strong><?=$ord->status;?></strong></p>
                        </div>
                    </div>

                </div>
				<?
			}
		}


	}



?>



