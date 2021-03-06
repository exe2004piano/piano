<?php
/**
* @version      4.1.0 20.11.2011
* @author       MAXXmarketing GmbH
* @package      Jshopping
* @copyright    Copyright (C) 2010 webdesigner-profi.de. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');

class jshopCategory extends JTableAvto{

    function __construct( &$_db ){
        parent::__construct( '#__jshopping_categories', 'category_id', $_db );
    }

    function getSubCategories($parentId, $order = 'id', $ordering = 'asc', $publish = 0) {
        $lang = JSFactory::getLang();
        $user = JFactory::getUser();
        $add_where = ($publish)?(" AND category_publish = '1' "):("");
        $groups = implode(',', $user->getAuthorisedViewLevels());
        $add_where .=' AND access IN ('.$groups.')';
        if ($order=="id") $orderby = "category_id";
        if ($order=="name") $orderby = "`".$lang->get('name')."`";
        if ($order=="ordering") $orderby = "ordering";
        if (!$orderby) $orderby = "ordering";

        $query = "SELECT `".$lang->get('name')."` as name,`".$lang->get('description')."` as description,`".$lang->get('short_description')."` as short_description, category_id, category_publish, ordering, category_image FROM `#__jshopping_categories`
                   WHERE category_parent_id = '".$this->_db->escape($parentId)."' ".$add_where."
                   ORDER BY ".$orderby." ".$ordering;
        $this->_db->setQuery($query);
        $categories = $this->_db->loadObjectList();
        foreach($categories as $key=>$value){
            $categories[$key]->category_link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$categories[$key]->category_id, 1);
        }
        return $categories;
    }

    function getName() {
        $lang = JSFactory::getLang();
        $name = $lang->get('name');
        return $this->$name;
    }

    function getDescription(){

        if (!$this->category_id){
            $this->getDescriptionMainPage();
            return 1;
        }

        $lang = JSFactory::getLang();
        $name = $lang->get('name');
        $description = $lang->get('description');
        $short_description = $lang->get('short_description');
        $meta_title = $lang->get('meta_title');
        $meta_keyword = $lang->get('meta_keyword');
        $meta_description = $lang->get('meta_description');

        $this->name = $this->$name;
        $this->description = $this->$description;
        $this->short_description = $this->$short_description;
        $this->meta_title = $this->$meta_title;
        $this->meta_keyword = $this->$meta_keyword;
        $this->meta_description = $this->$meta_description;
    }

    function getTreeChild() {
        $category_parent_id = $this->category_parent_id;
        $i = 0;
        $list_category = array();
        $list_category[$i] = new stdClass();
        $list_category[$i]->category_id = $this->category_id;
        $list_category[$i]->name = $this->name;
        $i++;
        while($category_parent_id) {
            $category = JTable::getInstance('category', 'jshop');
            $category->load($category_parent_id);
            $list_category[$i] = new stdClass();
            $list_category[$i]->category_id = $category->category_id;
            $list_category[$i]->name = $category->getName();
            $category_parent_id = $category->category_parent_id;
            $i++;
        }
        $list_category = array_reverse($list_category);
        return $list_category;
    }

    function getAllCategories($publish = 1, $access = 1) {
        $db = JFactory::getDBO();
        $user = JFactory::getUser();
        $where = array();
        if ($publish){
            $where[] = "category_publish = '1'";
        }
        if ($access){
            $groups = implode(',', $user->getAuthorisedViewLevels());
            $where[] =' access IN ('.$groups.')';
        }
        $add_where = "";
        if (count($where)){
            $add_where = " where ".implode(" and ", $where);
        }
        $query = "SELECT category_id, category_parent_id FROM `#__jshopping_categories` ".$add_where." ORDER BY ordering";
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    function getChildCategories($order='id', $ordering='asc', $publish=1){
        return $this->getSubCategories($this->category_id, $order, $ordering, $publish);
    }

    function getSisterCategories($order, $ordering = 'asc', $publish = 1) {
        return $this->getSubCategories($this->category_parent_id, $order, $ordering, $publish);
    }

    function getTreeParentCategories($publish = 1, $access = 1){
        $user = JFactory::getUser();
        $cats_tree = array();
        $category_parent = $this->category_id;
        $where = array();
        if ($publish){
            $where[] = "category_publish = '1'";
        }
        if ($access){
            $groups = implode(',', $user->getAuthorisedViewLevels());
            $where[] =' access IN ('.$groups.')';
        }
        $add_where = "";
        if (count($where)){
            $add_where = "and ".implode(" and ", $where);
        }
        while($category_parent) {
            $cats_tree[] = $category_parent;
            $query = "SELECT category_parent_id FROM `#__jshopping_categories` WHERE category_id = '".$this->_db->escape($category_parent)."' ".$add_where;
            $this->_db->setQuery($query);
            $rows = $this->_db->loadObjectList();
            $category_parent = $rows[0]->category_parent_id;
        }
        return array_reverse($cats_tree);
    }

    function getProducts($filters, $order = null, $orderby = null, $limitstart = 0, $limit = 0) {
		// --- ???????????? ???????????????????????? ???????????? ??????????
    	return false;
        $jshopConfig = JSFactory::getConfig();
        $adv_query = ""; $adv_from = ""; $adv_result = $this->getBuildQueryListProductDefaultResult();
        $this->getBuildQueryListProduct("category", "list", $filters, $adv_query, $adv_from, $adv_result);
        $order_query = $this->getBuildQueryOrderListProduct($order, $orderby, $adv_from);

        JPluginHelper::importPlugin('jshoppingproducts');
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger('onBeforeQueryGetProductList', array("category", &$adv_result, &$adv_from, &$adv_query, &$order_query, &$filters) );

/*
                    $sklad[0] = '??? ?????????????????????';
                    $sklad[1] = '????????? ??????????????? ?????? ' . date("d.m.Y", time() + 7*24*60*60);
                    $sklad[2] = '????????? ??? ?????????????????????';
                    $sklad[3] = '???????????? ??? ????????????????????????????????????';
                    $sklad[4] = '????????? ???????????????';
*/


        $cur_attr = 1*$_COOKIE['attr_id'];
        $cur_field = 1*$_COOKIE['field_id'];
        $cur_cat = 1*$_COOKIE['cat_id'];

        $attr_where = "";
        if( ($cur_field>0) && ($cur_cat==$this->category_id*1) )
        {
            $attr_where = " AND prod.extra_field_{$cur_field}={$cur_attr} ";
        }

        // --- ???????????????? ???????????? ???? ????????????
        if(isset($_COOKIE['show_all_sklad']))
            $show_sklad_only = " AND prod.sklad<=1 ";
        else
            $show_sklad_only = "";

        // --- ???????? ???????????? product_type ???? ?????????????? ???? ????????
        $product_type_from = "";
        $product_type = 1*$_GET['product_type'];
        if($product_type>0)
        {
            $product_type_from = " AND (pr_attr2.attr_value_id={$product_type}) ";
        }


        $query = "SELECT $adv_result, prod.price_reg, prod.sklad sklad, prod.cz FROM `#__jshopping_products` AS prod
                  LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)
                  LEFT JOIN `#__jshopping_products_attr2` AS pr_attr2 USING (product_id)
                  $adv_from
                  WHERE pr_cat.category_id = '".$this->_db->escape($this->category_id)."' AND prod.product_publish = '1' {$show_sklad_only} {$attr_where} {$product_type_from} ".$adv_query." ".$order_query;

		$query = str_replace("ORDER BY",
		"ORDER BY CASE
		WHEN sklad=3 THEN 100
		WHEN sklad=2 THEN 90
		WHEN sklad=5 THEN 85
		WHEN sklad=4 THEN 80
		WHEN sklad=1 THEN 70
		ELSE 0
		END, " , $query);


        if ($limit){
            $this->_db->setQuery($query, $limitstart, $limit);
        }else{
            $this->_db->setQuery($query);
        }
        $products = $this->_db->loadObjectList();
        $products = listProductUpdateData($products);

        return $products;
    }

    function getCountProducts($filters){
        $jshopConfig = JSFactory::getConfig();
        $adv_query = ""; $adv_from = ""; $adv_result = "count(*)";
        $this->getBuildQueryListProduct("category", "count", $filters, $adv_query, $adv_from, $adv_result);

        $cur_attr = 1*$_COOKIE['attr_id'];
        $cur_field = 1*$_COOKIE['field_id'];
        $cur_cat = 1*$_COOKIE['cat_id'];

        $attr_where = "";
        if( ($cur_field>0) && ($cur_cat==$this->category_id*1) )
        {
            $attr_where = " AND prod.extra_field_{$cur_field}={$cur_attr} ";
        }


        // --- ???????????????? ???????????? ???? ????????????
        if(isset($_COOKIE['show_all_sklad']))
            $show_sklad_only = " AND prod.sklad<=1 ";
        else
            $show_sklad_only = "";


        // --- ???????? ???????????? product_type ???? ?????????????? ???? ????????
        $product_type_from = "";
        $product_type = 1*$_GET['product_type'];
        if($product_type>0)
        {
            $product_type_from = " AND (pr_attr2.attr_value_id={$product_type}) ";
        }


        JPluginHelper::importPlugin('jshoppingproducts');
        $dispatcher = JDispatcher::getInstance();
        $dispatcher->trigger( 'onBeforeQueryCountProductList', array("category", &$adv_result, &$adv_from, &$adv_query, &$filters) );

        $query = "SELECT $adv_result, prod.sklad sklad, prod.cz
                  FROM `#__jshopping_products_to_categories` AS pr_cat
                  INNER JOIN `#__jshopping_products` AS prod ON pr_cat.product_id = prod.product_id
                  LEFT JOIN `#__jshopping_products_attr2` AS pr_attr2 ON prod.product_id = pr_attr2.product_id
                  $adv_from
                  WHERE pr_cat.category_id = '".$this->_db->escape($this->category_id)."' AND prod.product_publish = '1' " . $show_sklad_only . $attr_where . $adv_query . $product_type_from;
        $this->_db->setQuery($query);
        return $this->_db->loadResult();
    }

    function getDescriptionMainPage(){
        $statictext = JTable::getInstance("statictext","jshop");
        $row = $statictext->loadData("home");
        $this->description = $row->text;

        $seo = JTable::getInstance("seo","jshop");
        $row = $seo->loadData("category");
        $this->meta_title = $row->title;
        $this->meta_keyword = $row->keyword;
        $this->meta_description = $row->description;
    }

    /**
    * get List Manufacturer for this category
    */
    function getManufacturers(){
        $jshopConfig = JSFactory::getConfig();
        $user = JFactory::getUser();
        $lang = JSFactory::getLang();
        $adv_query = "";
        $groups = implode(',', $user->getAuthorisedViewLevels());
        $adv_query .=' AND prod.access IN ('.$groups.')';
        if ($jshopConfig->hide_product_not_avaible_stock){
            $adv_query .= " AND prod.product_quantity > 0";
        }
        if ($jshopConfig->manufacturer_sorting==2){
            $order = 'name';
        }else{
            $order = 'man.ordering';
        }
        $query = "SELECT distinct man.manufacturer_id as id, man.`".$lang->get('name')."` as name, prod.sklad as sklad, prod.cz as cz FROM `#__jshopping_products` AS prod
                  LEFT JOIN `#__jshopping_products_to_categories` AS categ USING (product_id)
                  LEFT JOIN `#__jshopping_manufacturers` as man on prod.product_manufacturer_id=man.manufacturer_id
                  WHERE categ.category_id = '".$this->_db->escape($this->category_id)."' AND prod.product_publish = '1' AND prod.product_manufacturer_id!=0 ".$adv_query." order by ".$order;
        $this->_db->setQuery($query);
        $list = $this->_db->loadObjectList();
        return $list;

    }
}
?>