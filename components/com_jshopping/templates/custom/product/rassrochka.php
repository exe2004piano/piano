<?php
defined( '_JEXEC' ) or die();
	/*
	подсчет рассрочки по логике:
	1. если у товара указана формула - то считаем по ней
	2. если нет, то смотрим формулу у родительской категории
	3. если нет - то у родителя следующего уровня и так далее
	4. если вообще нет - то смотрим глобально из конфига
	*/

	$rassrochka = "";
	$product->rassrochka = trim($product->rassrochka);
	if($product->rassrochka!='')
		$rassrochka = $product->rassrochka;
	else
	{
		$db->setQuery("SELECT rassrochka, category_parent_id FROM #__jshopping_categories WHERE category_id={$product->product_categories[0]->category_id}");
		$c = $db->loadObject();
		$c->rassrochka = trim($c->rassrochka);
		if($c->rassrochka!='')
			$rassrochka = $c->rassrochka;
		else
		{
			$db->setQuery("SELECT rassrochka, category_parent_id FROM #__jshopping_categories WHERE category_id={$c->category_parent_id}");
			if($c = $db->loadObject())
			{
				$c->rassrochka = trim($c->rassrochka);
				if($c->rassrochka!='')
					$rassrochka = $c->rassrochka;
			}
		}
	}

	// --- если ни товар, ни родитель, ни прородитель не указаны - тогда обратимся в глобальный конфиг
	if($rassrochka=='')
	{
		$db->setQuery("SELECT * FROM #__z_config WHERE name='rassrochka_formula'");
		$r = $db->loadObject();
		$rassrochka = $r->value;
	}

	// --- если на каком-то этапе нашелся 0 - то публиковать рассрочку не нужно
	if($rassrochka == '0')
		return;

	// --- теперь формула рассрочки точно какая-то есть
	// --- вид её : мес=price*формула
	// --- например: 6=price/6*1.01+5 означает рассрочку на 6 мес. по формуле : (цена+1%)/6 + 5 руб. в месяц
	$rassrochka_all = explode("\n", $rassrochka);

	$class = " is--active ";
	$buttons = " ";
	if(sizeof($rassrochka_all)>1)
		$buttons = " buttons ";
	$n = 0;

$rassrochka_variants = '';
?>
<div class="inf__body-row" id="row-select">
    <div class="bv-select">
        <div class="bv-select__nav">
            <a href="#" class="bv-select__up">
                <svg class="select-icon">
                    <use xlink:href="/templates/pianino_new/i/sprite.svg#selectArrow"></use>
                </svg>
            </a>
            <a href="#" class="bv-select__down">
                <svg class="select-icon">
                    <use xlink:href="/templates/pianino_new/i/sprite.svg#selectArrow"></use>
                </svg>
            </a>
        </div>
<?
foreach($rassrochka_all AS $r_id=>$rassrochka)
{
    if(trim($rassrochka)=='')
        continue;

    $temp = explode("=", $rassrochka);
    $temp[1] = str_replace("price", (1.0*$product->product_price), $temp[1]);
    $res = '1';
    $str = '$res='.$temp[1].";";
    eval($str);
    $res = number_format($res, 2, ".", " ");

    $n++;
    $text = "";
    $text .=
    "<a 
        rel='{$n}' 
        id='rassrochka_{$n}' 
        data-get-popup=\"paymentInParts\" 
        data-part=\"{$r_id}\" 
        href='#' 
        class='bv-select__item {$class} {$buttons}' 
        onclick=\" fbq('track','Lead'); $('#rassrochka_var_{$n}').prop('checked', true); event_send('kupit_rassrochka', 'kupitRassrochka');  $('#product_rassrochka').val('".str_replace(Array("'", '"'), " ", $product->name. " в рассрочку: ".$temp[0]."x".$res."р.")."'); $('#product_rassrochka_id').val('{$product->product_id}'); $('#product_rassrochka_price').val('{$product->product_price}');\" 
    >";
    $text .= "В рассрочку <strong>{$temp[0]}x {$res} р. </strong>";
    $text .= "</a>";

    $checked = "";
    if($class!="")
        $checked = " CHECKED ";

    $rassrochka_variants .= " 
           <div class=\"checkbox-list__item\">
                <input data-prod='".str_replace(Array("'", '"', '`'), "",$product->name)." в рассрочку ".$temp[0]."x".$res."р.' rel='{$n}' type='radio' type=\"checkbox\" name='rassrochka_var' id='rassrochka_var_{$n}' {$checked} class='rassrochka_var_input'>
                <label rel='{$n}' for='rassrochka_var_{$n}'>Рассрочка <strong data-part=\"1\">{$temp[0]} x{$res}р.</strong></label>
           </div>";

    $class = "";
    echo $text;
}
?>
    </div>
</div>
<?


echo "<div id='rassrochka_variants_hidden' style='display: none;'> {$rassrochka_variants}</div>";
?>

<script>
    $(document).on("ready", function(){
        $("#product_rassrochka_title").html('<?=str_replace(Array("'", '"'), " ", $product->name);?>');
    });
</script>




