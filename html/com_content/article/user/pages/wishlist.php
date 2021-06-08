<? defined( '_JEXEC' ) or die();
    global $db;
	if(!$user = get_current_user_z())
	{
		$app = JFactory::getApplication();
		$app->redirect(JRoute::_("/login"));
	}


	if(!function_exists('translit'))
	{
		function translit($string)
		{
			$replace = array(
				"&#039;" => "_",
				"&#8203;" => "_",
				"&quot;" => "_",
				"&mdash;" => "_",
				"&nbsp;" => " ",
				"&amp;" => "_",
				"…" => "_",
				"&" => "_",
				"#" => "_",
				";" => "_",
				"[" => "_",
				"]" => "_",
				")" => "_",
				"(" => "_",
				"—" => "_",
				"–" => "_",
				"№" => "_",
				"+" => "_",
				"·" => "",
				"
        " => "",
				"$" => "_",
				"-" => "_",
				"%" => "_",
				"`" => "",
				"'" => "_",
				"«" => "",
				"»" => "",
				"," => "",
				" " => "_",
				"/" => "_",
				'\\' => "",
				"\"" => "",
				"=" => "",
				"?" => "",
				"." => "",
				"!" => "",
				":" => "",
				"," => "",
				"а" => "a", "А" => "a",
				"б" => "b", "Б" => "b",
				"в" => "v", "В" => "v",
				"г" => "g", "Г" => "g",
				"д" => "d", "Д" => "d",
				"е" => "e", "Е" => "e",
				"ё" => "e", "Ё" => "e",
				"ж" => "zh", "Ж" => "zh",
				"з" => "z", "З" => "z",
				"и" => "i", "И" => "i",
				"й" => "y", "Й" => "y",
				"к" => "k", "К" => "k",
				"л" => "l", "Л" => "l",
				"м" => "m", "М" => "m",
				"н" => "n", "Н" => "n",
				"о" => "o", "О" => "o",
				"п" => "p", "П" => "p",
				"р" => "r", "Р" => "r",
				"с" => "s", "С" => "s",
				"т" => "t", "Т" => "t",
				"у" => "u", "У" => "u",
				"ф" => "f", "Ф" => "f",
				"х" => "h", "Х" => "h",
				"ц" => "c", "Ц" => "c",
				"ч" => "ch", "Ч" => "ch",
				"ш" => "sh", "Ш" => "sh",
				"щ" => "sch", "Щ" => "sch",
				"ъ" => "", "Ъ" => "",
				"ы" => "y", "Ы" => "y",
				"ь" => "", "Ь" => "",
				"э" => "e", "Э" => "e",
				"ю" => "yu", "Ю" => "yu",
				"я" => "ya", "Я" => "ya",
				"і" => "i", "І" => "i",
				"ї" => "yi", "Ї" => "yi",
				"є" => "e", "Є" => "e"
			);

			$string = str_replace(array("'", '"'), "", $string);
			$str = iconv("UTF-8", "UTF-8//IGNORE", strtr($string, $replace));
			$str = trim(preg_replace("/[^a-zA-Z0-9]/u", "-", $str));
			for ($i = 0; $i < 10; $i++)
				$str = str_replace("--", "-", $str);
			return strtolower($str);
		}
	}

	$new_like = explode("~", trim($_COOKIE['new_like']));
	foreach ($new_like AS $id=>$n)
    {
        $n = (int)$n;
        if($n<=0)
            unset($new_like[$id]);
    }

	if(sizeof($new_like)<1)
	{
		echo "У Вас нет товаров в избранных<br />";
		return;
	}

	$ids = "";
	foreach($new_like AS $c)
		if(1*$c>0)
			$ids .= (1*$c) . ", ";

	$ids .= "-1";


	$q =
		"
SELECT 
p.*, p.`name_ru-RU` title, c.category_id, l.name label_name,
cat.`name_ru-RU` cat_name, cat1.`name_ru-RU` cat1_name,
cat2.`name_ru-RU` cat2_name, cat3.`name_ru-RU` cat3_name
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
LEFT JOIN #__jshopping_product_labels AS l ON p.label_id=l.id
LEFT JOIN #__jshopping_categories AS cat ON cat.category_id=c.category_id
LEFT JOIN #__jshopping_categories AS cat1 ON cat1.category_id=cat.category_parent_id
LEFT JOIN #__jshopping_categories AS cat2 ON cat2.category_id=cat1.category_parent_id
LEFT JOIN #__jshopping_categories AS cat3 ON cat3.category_id=cat2.category_parent_id
WHERE p.product_id IN ({$ids}) AND p.product_publish=1
";

	$db->setQuery($q);
	if(!$products = $db->loadObjectList())
    {
		echo "Данные товары устарели или никогда не были представлены на сайте<br />";
		return;
    }


	$cats = Array();
    foreach ($products AS $id=>$product)
    {
        if($product->cat3_name)
            $cat = $product->cat3_name;
        elseif($product->cat2_name)
		    $cat = $product->cat2_name;
        elseif($product->cat1_name)
			$cat = $product->cat1_name;
        elseif($product->cat_name)
			$cat = $product->cat_name;

		$cats[$cat] = $cat;
		$products[$id]->cat = $cat;
    }

?>


    <? if(sizeof($cats)>1) { ?>
        <div class="layout__links">
            <a href="#" data-cat="0" class="cat_link layout__link is--active">Все</a>
            <? foreach ($cats AS $cat) { $cat_alias = translit($cat); ?>
                <a href="#" data-cat="<?=$cat_alias;?>" class="cat_link layout__link <?//is--active;?>"><?=$cat;?></a>
            <? } ?>
        </div>
    <? } ?>

    <div class="list list--second">
        <?php
			$text = '';
			foreach($products AS $product)
			{
				$product->like_product=1;
				// $text .= include(JPATH_ROOT.'/exe/product_in_slider.php');
				$z_cat_alias = translit($product->cat);
				$text .= include(JPATH_ROOT.'/components/com_jshopping/exe_product.php');
				$temp_prod = $product;
			}
			echo $text;
        ?>

    </div>

