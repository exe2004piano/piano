<?php

defined('_JEXEC') or die;
$db = JFactory::getDBO();
global $all_extra_names;
global $all_extra;


// --- выберем все характеристики
// --- это накладно для памяти, но иначе мы будем делать 100500 обращений к базе
// --- в общем так быстрее
// --- даже если значений характеристик будет 5000 - это 500кб памяти максимум
// --- зато прирост в скорости норм
$db->setQuery("SELECT id, field_id, `name_ru-RU` title FROM #__jshopping_products_extra_field_values ORDER BY field_id, ordering");
$res = $db->loadObjectList();
foreach($res AS $e)
    $all_extra[$e->field_id][$e->id]=$e->title;


$db->setQuery("SELECT id, `name_ru-RU` title FROM #__jshopping_products_extra_fields ORDER BY id");
$res = $db->loadObjectList();
foreach($res AS $e)
    $all_extra_names[$e->id]=$e->title;
unset($res);
global $sr_products;
$sr_products = '';
if(!isset($_GET['sr_products']))
{
    $compare = explode("~", trim($_COOKIE['new_compare']));
    if (!$compare)
        return "Вы не выбрали товары для сравнения";
}
else
{
    $sr_products = ' checked="true" ';
    $compare = $_GET['sr_products'];
}

$ids = "";
foreach($compare AS $c)
    if(1*$c>0)
        $ids .= (1*$c) . ", ";

if($ids=="")
    return "Вы не выбрали товары для сравнения";


$text = "";



	$ids .= "-1";


	$q =
		"
SELECT p.*, p.`name_ru-RU` title, c.category_id, l.name label_name
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
LEFT JOIN #__jshopping_product_labels AS l ON p.label_id=l.id
WHERE p.product_id IN ({$ids}) AND p.product_publish=1
";

	$db->setQuery($q);
	if(!$products = $db->loadObjectList())
		return "Данные товары устарели или никогда не были представлены на сайте";



?>


<div class="layout__item" style="width: 100%; max-width: 100%; margin-left: 0px;">
    <? /*
    <div class="layout__links">
        <a href="#" class="layout__link is--active">Цифровые пианино</a>
        <a href="#" class="layout__link">Синтезаторы</a>
        <a href="#" class="layout__link">Органы</a>
    </div> */?>

    <div class="tbl">
        <div class="tbl__head">
            <a class="tbl__head-item tbl__head-link" href="/cifrovye-pianino">
                <svg class="piano-icon">
                    <use class="piano-icon__part" href="/templates/pianino_new/i/sprite.svg#piano"></use>
                </svg>
                <p>Добавить еще одну модель</p>
            </a>

            <div class="tbl__head-item">
                <div class="list list--third">


                    <? foreach ($products AS $product) { ?>
                        <? $temp_prod = $product; ?>
                        <? include __DIR__.'/prod_in_compare.php'; ?>
                    <? } ?>

                </div>
            </div>
        </div>


        <div class="tbl__body">


            <div class="tbl__body-item tbl__body--sticky">
                <div class="tbl-tabs">
                    <div class="tbl-tabs__tab">
                        <a href="#" class="tbl-tabs__link is--active" data-show-link="true">Все параметры</a>
                    </div>

                    <div class="tbl-tabs__tab">
                        <a href="#" class="tbl-tabs__link" data-show-link="false">Только отличия</a>
                    </div>
                </div>


                <?
                    $extra_array = Array();
                    // --- пройдемся по всем экстрафилдам и посмотрим есть ли такой хотя бы в 1 товаре

                    for($i=1;$i<1000;$i++)
                    {
						$e_id = "extra_field_".$i;

						if(isset($temp_prod->$e_id))
                        {
                            $isset = false;

							$data_show = 'data-show="true"';
							$data_arr = Array();
                            foreach ($products AS $id=>$product)
                            {
								$data_arr[(int)$product->$e_id] = 1;
                                if(((int)$product->$e_id)!=0)
                                    $isset = true;
                            }

							if(sizeof($data_arr)>1)
								$data_show = '';

							if($isset)
							    $extra_array[$i] = Array(
							            "name"          =>  $all_extra_names[$i],
                                        "data_show"     =>  $data_show,
                                );
                        }
                    }
                    // --- теперь $extra_array - массив [id]=>extra_name всех заполненных параметров, выведем их:
                ?>

                <? foreach ($extra_array AS $e) { ?>
                    <div class="tbl__body-row" <?=$e['data_show'];?> >
                        <p><?=$e['name'];?></p>
                    </div>
                <?} ?>


            </div>



            <? // --- теперь по каждому товару ?>
            <? foreach ($products AS $product) { ?>
            <div class="tbl__body-item">

                <? // --- выведем каждое значение есть оно есть ?>
                <? foreach ($extra_array AS $e_id=>$e) { ?>
                <div class="tbl__body-row" <?=$e['data_show'];?> >
                    <p>&nbsp;<?
                            $e_name = "extra_field_{$e_id}";
                            if( ((int)$product->$e_name)>0 )
                                echo $all_extra[$e_id][(int)$product->$e_name];
                    ?>&nbsp;</p>
                </div>
                <? } ?>

            </div>
            <? } ?>



        </div>
    </div>

    <? /* <a href="#" class="bv-btn bv-btn--fourth">Выйти</a> */ ?>
</div>



































<? return;

$text .=
    '<div class="b-section__title">
		<span>Товары в сравнении</span>
		<a href="#" class="b-section__titleLink--print">Распечатать</a>
		<a href="#" class="b-section__titleLink--mail">Прислать на Email</a>
	</div>';

$ids .= "-1";


$q =
    "
SELECT p.*, p.`name_ru-RU` title, c.category_id, l.name label_name
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
LEFT JOIN #__jshopping_product_labels AS l ON p.label_id=l.id
WHERE p.product_id IN ({$ids}) AND p.product_publish=1
";

$db->setQuery($q);
if(!$products = $db->loadObjectList())
    return "Данные товары устарели или никогда не были представлены на сайте";



$text .=
    '<div class="b-compare__content">
        <div class="b-compare__titleBlock">
            <div class="b-compare__titleAdd">
                <a href="/cifrovye-pianino" class="b-compare__titleAdd-link"><span>добавить еще один товар</span></a>
                <div class="b-compare__checkWrap">
                    <input type="checkbox" id="compare" hidden '.$sr_products.'>
                    <label for="compare" onclick="setTimeout(function(){diff_items();}, 100);">Только различающиеся</label>
                </div>
            </div>
            <div class="b-compare__titleList b-item__contentList" data-screen="screenItems">
            ';


foreach($products AS $product)
{
    $temp_prod = $product;
    $product->compare=1;
    // $text .= include(JPATH_ROOT.'/exe/product_in_slider.php');
    $text .= include('exe_product.php');
}





$text .= "</div>";

$text .= '<div class="b-compare__table">';

for($i=1;$i<300;$i++)
{
    $extra_n = "extra_field_{$i}";
    if(isset($temp_prod->$extra_n))
    {
        // --- есть такой экстрафилд, проверим есть ли хотя бы 1 наш товар, у которого он ненулевой:
        $done = 0;
        foreach($products AS $product)
            if( ($product->$extra_n!='') && ($product->$extra_n!='0') )
                $done = 1;


        if($done==1)
        {
            // --- кого-то с ненулем нашли, выведем этот экстрафилд:
            // --- сначала соберем значения, и сравним их:
            $values = null;
            $text_temp = "";
            foreach($products AS $product)
            {
                $text_temp .= '<div class="b-compare__td">'.$all_extra[$i][$product->$extra_n].'</div>';
                $values[] = $all_extra[$i][$product->$extra_n];
            }

            $values = array_unique($values);

            if(sizeof($values)<2)
                $razn = " diff_items ";
            else
                $razn = "";


            $text .=
            '<div class="b-compare__tr '.$razn.'">
							<div class="b-compare__th">'.$all_extra_names[$i].'</div>';

            $text .= $text_temp;

            $text .='</div>' . "\n";
        }
    }
}
$text .= '</div>';






$text .= '
	</div>
</div>
';


$text .= "

<script>
    setTimeout(function(){diff_items();}, 10);
</script>

";

return $text;