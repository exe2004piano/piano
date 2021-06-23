<?php

    defined('_JEXEC') or die;
	
	
    // 
	if (!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php'))
	{
		// 
        JError::raiseError(500, "Please install component \"joomshopping\"");
    } 
    // 
    require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php'); 
	// 
    require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');        
	// 
    JSFactory::loadCssFiles();
	// 
    JSFactory::loadLanguageFile();
	// 
    $jshopConfig = JSFactory::getConfig();
    // 
    $product = JTable::getInstance('product', 'jshop');
	// 
	$params_products = $params->get('products');
	// 
	$params_products = $params_products->groups;
	// 
	$ids = array();
	// 
	foreach ($params_products as $group)
	{
		$group = (array)$group;
		// 
		$ids = array_merge($ids, array_keys((array)$group['products']));
	}
	
	// 
	$db = JFactory::getDBO();
	// 
	$adv_query = ""; $adv_from = ""; $adv_result = $product->getBuildQueryListProductDefaultResult();
	$product->getBuildQueryListProductSimpleList("last", $array_categories, $filters, $adv_query, $adv_from, $adv_result);
	$order_query = "ORDER BY prod.product_id";
	// 
	JPluginHelper::importPlugin('jshoppingproducts');
	$dispatcher = JDispatcher::getInstance();
	$dispatcher->trigger('onBeforeQueryGetProductList', array("last_products", &$adv_result, &$adv_from, &$adv_query, &$order_query, &$filters));
	// 
	if (count($ids))
	{
		// 
		$query = "SELECT $adv_result FROM `#__jshopping_products` AS prod
					  INNER JOIN `#__jshopping_products_to_categories` AS pr_cat ON pr_cat.product_id = prod.product_id
					  LEFT JOIN `#__jshopping_categories` AS cat ON pr_cat.category_id = cat.category_id
					  $adv_from
					  WHERE prod.product_publish = '1' AND cat.category_publish='1' 
					  AND prod.product_id IN (".implode(',', $ids).")
					  GROUP BY prod.product_id $order_query DESC LIMIT 999";
		$db->setQuery($query);
		$products = $db->loadObjectList();
		// 
		$products = listProductUpdateData($products);
		// 
		$custom_products = array();
		// 
		foreach ($products as $product)
		{
			// 
			$custom_products[$product->product_id] = $product;
		}
		
		// 
		foreach ($custom_products as $product_id => $custom_product)
		{
			// 
			$custom_products[$product_id]->product_link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$custom_product->category_id.'&product_id='.$custom_product->product_id, 1);
		}
		// 
		$groups = array();
		// 
		$groups_description = array();
		// 
		$groups_height = array();
		// 
		foreach ($params_products as $param_group)
		{
			// 
			$groups[$param_group->title] = array();
			// 
			$groups_description[$param_group->title] = $param_group->description;
			// 
			$groups_height[$param_group->title] = $param_group->height;
			// 
			foreach ((array)$param_group->products as $product_id => $product)
			{
				// 
				$product = (array)$product;
				// 
				$groups[$param_group->title][$product_id] = $custom_products[$product_id];
				// 
				$groups[$param_group->title][$product_id]->title = $product['title'];
			}
		}
	}
	// 
    $noimage = "noimage.gif";
	// 
    $show_image = $params->get('show_image', 1);
	// 
    require(JModuleHelper::getLayoutPath('mod_jshopping_latest_products'));