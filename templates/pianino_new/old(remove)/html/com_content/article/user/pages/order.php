<? defined( '_JEXEC' ) or die();
	global $db;
	if(!$user = get_current_user_z())
	{
		$app = JFactory::getApplication();
		$app->redirect(JRoute::_("/login"));
	}

	$id = (int)$_GET['order_id'];
	if($id<=0)
	{
		$app = JFactory::getApplication();
		$app->redirect(JRoute::_("/login"));
	}


	$user->action = "get_order";
	$user->order_id = $id;
	if($info = get_user_info_crm($user))
	{
	    ?>
        <b>Заказ №<?=$info->id;?> от <?=date("d.m.Y", $info->date);?></b><br />
        <?
		foreach ($info->products AS $p)
		{
		    $site_prod = $db->setQuery("SELECT * FROM #__jshopping_products WHERE product_id={$p->product_id}")->loadObject();
			?>
			<div class="layout-row">
				<a href="<?=$site_prod->real_link;?>" class="layout-row__head">
					<img src="/components/com_jshopping/files/img_products/thumb_<?=$site_prod->image;?>"
                         alt=""
                         aria-hidden="true"
                    >
				</a>

				<a href="<?=$site_prod->real_link;?>" class="layout-row__body">
					<h4><?=$p->name;?> <? if($p->komplekt!='') { ?>+ Комплект<? } ?></h4>
                </a>

				<div class="layout-row__footer">
					<p><strong><?=$p->price;?> руб.</strong></p>
					<p>Количество: <strong><?=$p->num;?></strong></p>
				</div>

            </div>

			<? if($p->komplekt!='') { ?>
                <i class="komplekt"><?=$p->komplekt;?></i>
    		<? } ?>

			<?
		}
	}


?>
<style>
    i.komplekt {font-size: 12px!important; display: block!important; flex: 1;width: 100%!important; clear: both!important; overflow: hidden!important; float: none!important;}
</style>
