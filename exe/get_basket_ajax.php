<?php

if(!defined('_JEXEC'))
{
    define('_JEXEC', 1);
    define('DS', DIRECTORY_SEPARATOR);
    if (file_exists(dirname(__FILE__) . '/defines.php'))
    {
        include_once dirname(__FILE__) . '/defines.php';
    }
    if (!defined('_JDEFINES'))
    {
        define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
        require_once JPATH_BASE.'/includes/defines.php';
    }
    require_once JPATH_BASE.'/includes/framework.php';
    $app = JFactory::getApplication('site');
    $app->initialise();
}

$db = JFactory::getDBO();
$basket_res = '<span class="b-header__basketEmpty active">Ваша корзина пуста :(</span>' . "\n\n";
$summ = $num = "";

if(isset($_COOKIE['new_basket']))
{
    $summ = 0;
    $num = 0;
    $text = "";
// --- из корзины удалим всё кроме: цифр, ~, =, символа c (комплект)
    $basket = trim(preg_replace('/[^0-9=~_]/', '', $_COOKIE['new_basket']));
    $temp = explode("~", $basket);

    unset($items);
    unset($komplekts);


    foreach($temp AS $t)
        if(trim($t!=''))
        {
// --- получим массив ID => kolvo
            $arr = explode("=", $t);
            if(trim($arr[0])!='')
            {
                if(strpos("  ".$arr[0], "_")<1)
                    $items[$arr[0]] = $arr[1];
                else
                    $komplekts[$arr[0]] = $arr[1];
            }
        }

    if($items)
    {
        $basket_res = $basket_res_new = '';
        // $basket_res .= '<ul class="b-section__list">';


		$z_user = get_current_user_z();
        foreach($items AS $key=>$value)
        {
            $num += $value;
            $key = 1*$key;
            $q = "
    SELECT  p.*, p.`name_ru-RU` title, p.product_ean, p.`alias_ru-RU` alias, p.product_price, p.price_reg, p.image, cat.`alias_ru-RU` cat_alias, cat_parent.`alias_ru-RU` cat_parent_alias
    FROM #__jshopping_products p
    LEFT JOIN #__jshopping_products_to_categories AS c USING (product_id)
    LEFT JOIN #__jshopping_categories AS cat ON c.category_id=cat.category_id
    LEFT JOIN #__jshopping_categories AS cat_parent ON cat.category_parent_id=cat_parent.category_id
    WHERE p.product_id={$key}
    ";

            $db->setQuery($q);
            $res = $db->loadObject();

            if($z_user)
				$res->product_price  =  $res->price_reg;

			$pre_summ = $value*$res->product_price;
            $summ += $pre_summ;

			$old_price = "";
			if($res->product_old_price>0)
                $old_price = "
                    <div class=\"pop__up-item\">
                        <p data-price-sale>".echo_price($res->product_old_price, 1, -1, $res)."</p>
                    </div>
                    ";

			$basket_res_new .=
				"
        <div class=\"pop__up-middle\">
            <div class=\"pop__up-img\">
                <img src=\"/components/com_jshopping/files/img_products/{$res->image}\" alt=\"\">
            </div>
            <div class=\"pop__up-inf\">
                <h2>{$res->title}</h2>
                <div class=\"pop__up-items\">
                    <div class=\"pop__up-item\">
                        <p data-price>".echo_price($res->product_price, 1, -1, $res)."</p>
                    </div>

                    {$old_price}
                    
                </div>
            </div>
        </div>
		";


        }

/*
        $basket_res .= '
</ul>
<div class="b-header__basketLink-wrap">
    <a href="/basket" class="b-header__basketLink">ПЕРЕЙТИ В КОРЗИНУ</a>
</div>
';
*/
    }
}

if($summ*1.0>0)
    $summ = echo_price($summ, 1, -1);
else
    $summ = '';

/*
$basket_res .=
    '<input type="hidden" id="basket_get_summ" value="'.$summ.'" />
<input type="hidden" id="basket_get_num" value="'.$num.'" />';
*/

	$basket_res_new .= '<input type="hidden" id="basket_get_summ" value="'.$summ.'" />
<input type="hidden" id="basket_get_num" value="'.$num.'" />';

echo $basket_res_new;
?>

