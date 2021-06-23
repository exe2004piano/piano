<?php
/*
 * Сформируем вывод продукции для слайдера (закладки "Популярные", "Хиты" и т.д. на главной и в разделах джумшоппинга)
 * $products - это будет массив объектов-продуктов, найденных по каким-либо критериям, name_ru-RU переименован в title
 *
 * $label - объект метки
 *
 */

defined( '_JEXEC' ) or die();
$products_itog = "";

foreach($products AS $product)
{
    if(!isset($product->label_name))
        $product->label_name=$label->name;
    $products_itog .= include(JPATH_ROOT.'/exe/product_in_slider.php');
}

return $products_itog;