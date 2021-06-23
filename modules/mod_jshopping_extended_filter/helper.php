<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

class modJShopExtendedFilterHelper {

	function getAttribute($id) {
		if($id) {
			$db = JFactory::getDBO();

			$query = "SELECT * FROM #__jshopping_attr WHERE attr_id = {$id}";
			$db->setQuery($query);	

			return $db->loadObject();
		}
	}	
	
	function getAttributeValues($id) {
		if($id) {
			$db = JFactory::getDBO();
			$lang = JFactory::getLanguage();
			$lang_name = "name_".$lang->getTag();

			$query = "SELECT `{$lang_name}` as name, value_id, image FROM #__jshopping_attr_values WHERE attr_id = {$id} ORDER BY value_ordering ASC";
			$db->setQuery($query);	

			return $db->loadObjectList();			
		}
	}
	
	function getCharacteristic($id) {
		if($id) {
			$db = JFactory::getDBO();

			$query = "SELECT * FROM #__jshopping_products_extra_fields WHERE id = {$id}";
			$db->setQuery($query);	

			return $db->loadObject();
		}
	}
	
	function getCharacteristicValues($id) {
		if($id) {
			$db = JFactory::getDBO();
			$lang = JFactory::getLanguage();
			$lang_name = "name_".$lang->getTag();

			$query = "SELECT `{$lang_name}` as name, id FROM #__jshopping_products_extra_field_values WHERE field_id = {$id} ORDER BY ordering ASC";
			$db->setQuery($query);	

			return $db->loadObjectList();			
		}
	}

	function getLabels() {
		$db = JFactory::getDBO();

		$query = "SELECT * FROM #__jshopping_product_labels ORDER BY name ASC";
		$db->setQuery($query);
		$list = $db->loadObjectList();

		return $list;
	}

	function getAllManufacturers() {
		$db = JFactory::getDBO();
		$lang = JFactory::getLanguage();
		$lang_name = "name_".$lang->getTag();
		$lang_desc = "description_".$lang->getTag();
		$lang_short = "short_description_".$lang->getTag();

		$query = "SELECT manufacturer_id, manufacturer_url, manufacturer_logo, manufacturer_publish, `".$lang_name."` as name, `".$lang_desc."` as description,  `".$lang_short."` as short_description";
		$query .= " FROM `#__jshopping_manufacturers` WHERE manufacturer_publish = 1 ORDER BY ordering ASC";
		$db->setQuery($query);
		$list = $db->loadObjectList();

		return $list;
	}

	function buildTreeCategory($restrict, $restcat, $restsub) {
		$db = JFactory::getDBO();
		$lang = JFactory::getLanguage();
		$lang_name = "name_".$lang->getTag();
		$user = JFactory::getUser();
		
		$where = array();

		$where[] = "category_publish = '1'";

		$groups = implode(',', $user->getAuthorisedViewLevels());
		$where[] = ' access IN ('.$groups.')';
		
		if($restrict == 1 && $restcat != '') {
			$restcat = str_replace(" ", "", $restcat);
			$restcat = explode(",", $restcat);
			
			$restcats = '';
			foreach($restcat as $k=>$catid) {
				if($k == 0) {
					$restcats .= $catid;
				}
				else {
					$restcats .= ",".$catid;
				}
				
				if($restsub) {
					$childs = modJShopExtendedFilterHelper::getCategoryChildren($catid);
					$childs = implode(",", $childs);
					
					if($childs) {
						$restcats .= ",".$childs;
					}
				}
			}
			
			$where[] = ' category_id IN ('.$restcats.')';
		}

		$add_where = "";
		if (count($where)){
			$add_where = " WHERE ".implode(" and ", $where);
		}

		$query = "SELECT `".$lang_name."` as name, category_id, category_parent_id, category_publish FROM `#__jshopping_categories`
				  ".$add_where." ORDER BY category_parent_id, ordering";
		$db->setQuery($query);
		$all_cats = $db->loadObjectList();
		
		$categories = array();
		if(count($all_cats)) {
			foreach ($all_cats as $key => $value) {
				if(!$value->category_parent_id){
					modJShopExtendedFilterHelper::recurseTree($value, 0, $all_cats, $categories);
				}
			}
		}
		return $categories;
	}
	
	function recurseTree($cat, $level, $all_cats, &$categories) {
		$probil = '';

		for ($i = 0; $i < $level; $i++) {
			$probil .= '-- ';
		}

		$cat->name = ($probil . $cat->name);
		$cat->level = $level;
		$categories[] = $cat;

		foreach ($all_cats as $categ) {
			if($categ->category_parent_id == $cat->category_id) {
				modJShopExtendedFilterHelper::recurseTree($categ, ++$level, $all_cats, $categories);
				$level--;
			}
		}
		return $categories;
	}
	
	function getCategoryChildren($catid) {

		static $array = array();
		$catid = (int) $catid;
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = {$catid} AND category_publish = 1";

		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}

		foreach ($rows as $row) {
			array_push($array, $row->category_id);
			if (modJShopExtendedFilterHelper::hasChildren($row->category_id)) {
				modJShopExtendedFilterHelper::getCategoryChildren($row->category_id);
			}
		}
		
		return $array;
	}
	
	function hasChildren($id) {

		$id = (int) $id;
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__jshopping_categories WHERE category_parent_id = {$id} AND category_publish = 1";
		
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		if ($db->getErrorNum()) {
			echo $db->stderr();
			return false;
		}

		if (count($rows)) {
			return true;
		} else {
			return false;
		}
	}
	
	function getModuleParams($id) {
		$db = JFactory::getDBO();
		
		$query = "SELECT * FROM #__modules WHERE id = {$id}";
		$db->setQuery($query);
		$result = $db->loadObject();
		
		$moduleParams = json_decode($result->params);
		return $moduleParams;
	}
	
	function getMinPrice() {
		$min_price = 0;
	
		$db = JFactory::getDBO();

		$query = "SELECT product_price FROM #__jshopping_products WHERE product_publish = 1";
		$db->setQuery($query);	
		$result = $db->loadResultArray();
		
		if($result) {
			$min_price = $result[0];
			foreach($result as $price) {
				if($price < $min_price) {
					$min_price = $price;
				}
			}
		}

		return $min_price;
	}	
	
	function getMaxPrice() {
		$max_price = 0;
	
		$db = JFactory::getDBO();

		$query = "SELECT product_price FROM #__jshopping_products WHERE product_publish = 1";
		$db->setQuery($query);	
		$result = $db->loadResultArray();
		
		if($result) {
			$max_price = $result[0];
			foreach($result as $price) {
				if($price > $max_price) {
					$max_price = $price;
				}
			}
		}

		return $max_price;
	}	

}

?>