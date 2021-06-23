<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

	defined('_JEXEC') or die;
	include_once JPATH_ROOT.'/includes/functions.php';
	if(get_current_user_z())
		return;
	$app = JFactory::getApplication();
	$menu = $app->getMenu();
	$oActive = $menu->getActive();
	global $db;
	global $jshop_product_id;
	$skidka_reg = 0;

	if($oActive->query['option']=='com_jshopping' && $oActive->query['controller']=='category')
	{
		if (!$jshop_product_id)
		{
			$catid = (int)$oActive->query['category_id'];
			$test = $db->setQuery("
				SELECT c.skidka_reg, c1.skidka_reg c1_skidka_reg, c2.skidka_reg c2_skidka_reg, c3.skidka_reg c3_skidka_reg  
				FROM #__jshopping_categories AS c
				LEFT JOIN #__jshopping_categories AS c1 ON c1.category_id=c.category_parent_id
				LEFT JOIN #__jshopping_categories AS c2 ON c2.category_id=c1.category_parent_id
				LEFT JOIN #__jshopping_categories AS c3 ON c3.category_id=c2.category_parent_id
				WHERE c.category_id={$catid}
		")->loadObject();

			if ($test->skidka_reg > 0)
				$skidka_reg = $test->skidka_reg;
			elseif ($test->skidka_reg == -1 && $test->c1_skidka_reg > 0)
				$skidka_reg = $test->c1_skidka_reg;
			elseif ($test->skidka_reg == -1 && $test->c2_skidka_reg > 0)
				$skidka_reg = $test->c2_skidka_reg;
			elseif ($test->skidka_reg == -1 && $test->c3_skidka_reg > 0)
				$skidka_reg = $test->c3_skidka_reg;

			if($skidka_reg>0)
				$skidka_reg.="%";
		}
		elseif ($jshop_product_id > 0)
		{
			$test = $db->setQuery("
				SELECT product_price, price_reg  
				FROM #__jshopping_products
				WHERE product_id={$jshop_product_id}
			")->loadObject();

			if($test->product_price > $test->price_reg )
				$skidka_reg = round($test->product_price-$test->price_reg)." BYN";
		}
	}

	if($skidka_reg==0)
		return;
?>
<div class="container">
	<div class="new-banner">
		<svg width="80" height="79">
			<use href="/templates/pianino_new/i/sprite.svg#new-banner"></use>
			<use href="/templates/pianino_new/i/sprite.svg#new-banner2"></use>
		</svg>

		<div class="new-banner-text">
			<p>
				<?
					$t = $params->get('main_text');
					$t = str_replace("[skidka]", $skidka_reg, $t);
					echo $t;
				?>
			</p>
		</div>
	</div>
</div>
