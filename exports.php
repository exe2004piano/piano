<?php  return;
defined('_JEXEC') or die;
$db = JFactory::getDbo();
global $user;
global $is_manager;



if(isset($_GET['finder_links']))
{
    $links = explode("~", $_GET['finder_links']);
    $res = "";
    foreach($links AS $l)
        if(trim($l)!="")
        {
            $temp = explode("|", $l);
            $res .= SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$temp[0].'&product_id='.$temp[1]) . "~";
        }

    echo $res;
    die;
}



// обновление онлайнера будем делать каждые 30 минут
try
{
    $ftime = 0;

    if(file_exists($_SERVER['DOCUMENT_ROOT'] . "/onliner_price.json"))
        $ftime = filectime($_SERVER['DOCUMENT_ROOT'] . "/onliner_price.json");
	
    if( (time()-$ftime) > 1800 ) // каждые 30 минут обновляем данные
    {
		update_all_price_reg();	// --- тут же обновим цены на товары для "зарегистрированных" пользователей
        ob_start();
        include_once($_SERVER['DOCUMENT_ROOT']."/export_onliner.php");
        ob_clean();
        ob_end_clean();


        // --- если время с 2 до 7 утра - то обнулим таблицу сессии
        // --- это конечно костыль, но против непонятного роста таблицы сессий пока ничего не придумал
        $h = 1*date("H", time());
        if( ($h>2) && ($h<7) )
        {

        $db->setQuery("DELETE FROM #__session WHERE userid=0");
        $db->execute();

        }
    }


    // --- заодно проверим нет ли товаров с пустыми линками
    $db->setQuery(
        "
        SELECT p.product_id, c.category_id
        FROM #__jshopping_products AS p
        LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
        WHERE p.real_link=''
        ");
    if($res = $db->loadObjectList())
    {
        foreach($res AS $a)
        {
            $link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id);
            $db->setQuery("UPDATE #__jshopping_products SET real_link='{$link}' WHERE product_id={$a->product_id}");
            $db->execute();
        }
    }
}
catch (Exception $e)
{

}



if(isset($_GET['real_link']))
{
	    // --- обновим линки
    $db->setQuery(
        "
        SELECT p.product_id, c.category_id
        FROM #__jshopping_products AS p
        LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
        ");
    if($res = $db->loadObjectList())
    {
        foreach($res AS $a)
        {
            $link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id);
            $db->setQuery("UPDATE #__jshopping_products SET real_link='{$link}' WHERE product_id={$a->product_id}");
            $db->execute();
        }
    }

    die;
}


if(isset($_GET['yandex']))
{
    // --- Yandex Market Export YML:


//--- курс:
//    $db->setQuery("SELECT * FROM #__jshopping_currencies WHERE currency_id=2");
//    $res = $db->loadObject();
//    $curr = $res->currency_value*1;
    $curr = 1.0;

// 0-Склад, 1-Под заказ(Д), 2-Нет, 3-Снято, 4-Под заказ, 5-анонс

    $q =
        "SELECT
p.*, `p`.`name_ru-RU` title, `p`.`short_description_en-GB` text_en, c.category_id category_id
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id = p.product_id
LEFT JOIN #__jshopping_categories AS cat ON c.category_id = cat.category_id
WHERE cat.category_publish=1 AND p.sklad<>2 AND p.sklad<>3 AND p.sklad<>5 AND p.product_publish=1 AND p.product_price>70";

    $db->setQuery($q);
    $res = $db->loadObjectList();


    $imp = "ООО `Мьюзик Ленд`";


    echo '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="' . date("Y-m-d h:i:s") . '">
<shop>
<name>piano.by</name><company>ООО «Мьюзик Ленд»</company><url>piano.by</url><currencies><currency id="BYN" rate="1"></currency></currencies><categories><category id="1">Товар</category></categories>

<delivery-options>
		<option cost="0" days="0" />
</delivery-options>

<offers>
';

    foreach($res AS $a)
        if($a->product_id!=3140)
        {
            // 0-Склад, 1-Под заказ(Д), 2-Нет, 3-Снято, 4-Под заказ
            $deliv = "";
            if($a->sklad*1<=1) // --- есть на складе
            {
                $available = "true";
                $av_info = "";
            }
            else
            {
                // --- если нет на складе, то транслируем только синтезаторы и цифровые пианино :
                if(
                    (strpos(" ".SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id, 1), "cifrovye-pianino")<1) &&
                    (strpos(" ".SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id, 1), "sintezatory")<1)
                )
                {
                    continue;
                }



                $available = "false";
                $deliv = "
			<delivery-options>
				<option cost=\"0\" days=\"\" />
			</delivery-options>
            ";

                switch($a->sklad*1)
                {
                    case 1:
                        $av_info = "<sales_notes>Требуется предоплата</sales_notes>";
                        break;
                    case 4:
                        $av_info = "<sales_notes>Требуется предоплата</sales_notes>";
                        break;
                    default:
                        $av_info = "";
                        break;
                }
            }

            $w = "1";
            if( (strpos(" ".$a->title, "Casio")>0) || ((strpos(" ".$a->title, "Yamaha")>0)) )
                if( (strpos(" ".SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id, 1), "cifrovye-pianino")>0) ||
                    (strpos(" ".SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id, 1), "sintezatory")>0) )
                    $w="3";

            echo "<offer id=\"{$a->product_id}\" available=\"{$available}\">";
            echo "<url>https://piano.by" . SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id, 1) . "</url>";
            echo "<price>" . (round($a->product_price, 2)*$curr) . "</price>";
            /*
            if($a->product_old_price*1>0)
                echo "<oldprice>" . (round($a->product_old_price,2)*$curr) . "</oldprice>";
            */
            echo "<delivery>true</delivery>";
            echo "<manufacturer_warranty>P{$w}Y</manufacturer_warranty>";
//        echo "<local_delivery_cost>0</local_delivery_cost>";
            echo $deliv;
            echo "<currencyId>BYN</currencyId>";
            echo "<categoryId>1</categoryId>";
            echo "<picture>https://piano.by/components/com_jshopping/files/img_products/{$a->image}</picture>";
            echo "<name>" . str_replace(Array("&", "<", ">", "'", '"'), " ", $a->title) . "</name>";
            echo "<description>" . str_replace(Array("&", "<", ">", "'", '"'), " ", $a->text_en) . "</description>";
            echo $av_info;
            echo "</offer>\n";

        }


    echo "</offers>
</shop>
</yml_catalog>";

    die;
}

if(isset($_GET['yandex_market']))
{
    include_once JPATH_ROOT.'/z/export/yandex_market.php';
    die;
}

if(isset($_GET['feed']))
{
	include_once JPATH_ROOT.'/z/export/feed.php';
	die;
}

if(isset($_GET['dealby']))
{
    include_once JPATH_ROOT.'/z/export/dealby.php';
    die;
}

if(isset($_GET['retail_rocket']))
{
    include_once JPATH_ROOT.'/z/export/retail_rocket.php';
    die;
}

if(isset($_GET['yandex_direct']))
{
    // --- Yandex Direct Export YML:
    $curr = 1.0;

    // --- для директа сформируем перечень категорий со вложенностью
    $q = "
    SELECT c.category_id, c.`name_ru-RU` title, c.category_parent_id
    FROM #__jshopping_categories AS c
    WHERE c.category_publish=1
    ORDER BY c.category_parent_id, c.ordering
    ";
    $db->setQuery($q);
    $all = $db->loadObjectList();

    $cats = "";
     foreach($all AS $a)
    {
        $parent = "";
        if($a->category_parent_id>0)
            $parent = "parentId=\"{$a->category_parent_id}\"";

        $cats .= "<category id=\"{$a->category_id}\" {$parent}>{$a->title}</category>\n";
    }


// 0-Склад, 1-Под заказ(Д), 2-Нет, 3-Снято, 4-Под заказ, 5-анонс

    $q =
        "SELECT
p.*, `p`.`name_ru-RU` title, `p`.`short_description_en-GB` text_en, c.category_id category_id,
cat_parent1.category_id cp1, cat_parent2.category_id cp2, cat_parent3.category_id cp3, cat_parent4.category_id cp4, cat_parent4.category_parent_id cp5
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id = p.product_id
LEFT JOIN #__jshopping_categories AS cat ON c.category_id=cat.category_id

LEFT JOIN #__jshopping_categories AS cat_parent1 ON cat_parent1.category_id=cat.category_parent_id
LEFT JOIN #__jshopping_categories AS cat_parent2 ON cat_parent2.category_id=cat_parent1.category_parent_id
LEFT JOIN #__jshopping_categories AS cat_parent3 ON cat_parent3.category_id=cat_parent2.category_parent_id
LEFT JOIN #__jshopping_categories AS cat_parent4 ON cat_parent4.category_id=cat_parent3.category_parent_id

WHERE p.sklad=0 AND p.product_publish=1 ";

    $db->setQuery($q);
    $res = $db->loadObjectList();


    $imp = "ООО `Мьюзик Ленд`";


    echo '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="' . date("Y-m-d h:i:s") . '">
<shop>
<name>piano.by</name><company>ООО «Мьюзик Ленд»</company><url>piano.by</url><currencies><currency id="BYN" rate="1"></currency></currencies><categories><category id="1">Товар</category></categories>
' . $cats . '
<delivery-options>
    <option cost="0" days="0" />
</delivery-options>
<offers>
';

    foreach($res AS $a)
        if($a->product_id!=3140)
        {
            // Аксессуары - это ID 64
            // все вложенные в него категории не публикуем

            if(
            ($a->cp1*1==64) ||
            ($a->cp2*1==64) ||
            ($a->cp3*1==64) ||
            ($a->cp4*1==64) ||
            ($a->cp5*1==64)
            )
                continue;


			// echo "----".($a->real_link)."====";

            // 0-Склад, 1-Под заказ(Д), 2-Нет, 3-Снято, 4-Под заказ
            $deliv = "";
            if($a->sklad*1==0) // --- есть на складе
            {
                $available = "true";
                $av_info = "";
            }
            else
            {



                // --- если нет на складе, то транслируем только синтезаторы и цифровые пианино :
                if(
					(strpos('  '.$a->real_link, "cifrovye-pianino")<1)
					&&
					(strpos('  '.$a->real_link, "sintezatory")<1)
					&&
					(strpos('  '.$a->real_link, "smychkovye-instrumenty")<1)

                    //(strpos(" ".SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id, 1), "cifrovye-pianino")<1) &&
                    //(strpos(" ".SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id, 1), "sintezatory")<1) &&
					//(strpos(" ".SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id, 1), "smychkovye-instrumenty")<1)
                )
                {
                    continue;
                }



                $available = "false";
                $deliv = "
			<delivery-options>
				<option cost=\"0\" days=\"\" />
			</delivery-options>
            ";

                switch($a->sklad*1)
                {
                    case 1:
                        $av_info = "<sales_notes>Требуется предоплата</sales_notes>";
                        break;
                    case 4:
                        $av_info = "<sales_notes>Требуется предоплата</sales_notes>";
                        break;
                    default:
                        $av_info = "";
                        break;
                }
            }


            $a->title = trim(str_replace(
                Array(
                    "Цифровое пианино", "Электропиано", "Детское цифровое пианино", "Цифровой рояль",
                    "Синтезатор", "синтезатор", "MIDI-клавиатура", "Акустические пианино",  "Пианино акустическое",
                    "Акустический рояль", "Классическая гитара", "Гитара классическая", "Электроакустическая классическая гитара",
                    "Гитара акустическая", "Акустическая гитара", "Гитара электроакустическая",
                    "Электрогитара", "Бас-гитара", "Укулеле","Комбоусилители для клавишных", "Клавишный комбоусилитель", "Транзисторный комбоусилитель",
                    "Транзисторный комбоусилитель", "Гитарный комбоусилитель", "Гитарный комбоусилитель",
                    "Бас-гитарный комбоусилитель", "Активный студийный монитор", "Студийный монитор", "Акустическая система",
                    "Активная акустическая система", "Активные акустические системы", "Пара микрофонов", "Вокальная радиосистема",
                    "Головной микрофон", "Динамический вокальный микрофон", "Микрофон", "Радиосистема",
                    "USB-интерфейс", "Звуковая карта"
                ),
                "",
                $a->title
            ));



            $w = "1";
            if( (strpos(" ".$a->title, "Casio")>0) || ((strpos(" ".$a->title, "Yamaha")>0)) )
                if( (strpos(" ".SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id, 1), "cifrovye-pianino")>0) ||
                    (strpos(" ".SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id, 1), "sintezatory")>0) )
                    $w="3";

            echo "<offer id=\"{$a->product_id}\" available=\"{$available}\">";
            echo "<url>https://piano.by" . SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id, 1) . "</url>";
            echo "<price>" . (round($a->product_price, 2)*$curr) . "</price>";
            echo "<delivery>true</delivery>";
            echo "<manufacturer_warranty>P{$w}Y</manufacturer_warranty>";
//        echo "<local_delivery_cost>0</local_delivery_cost>";
            echo $deliv;
            echo "<currencyId>BYN</currencyId>";
            echo "<categoryId>{$a->category_id}</categoryId>";
            echo "<picture>https://piano.by/components/com_jshopping/files/img_products/{$a->image}</picture>";
            echo "<name>" . str_replace(Array("&", "<", ">", "'", '"'), " ", $a->title) . "</name>";
            echo "<description>" . str_replace(Array("&", "<", ">", "'", '"'), " ", $a->text_en) . "</description>";
            echo $av_info;
            echo "</offer>\n";

        }


    echo "</offers>
</shop>
</yml_catalog>";

    die;
}

if(isset($_GET['unishop']))
{
    // --- unishop (like Yandex) Export YML:
    $curr = 1.0;

    $q =
        "SELECT
p.*, `p`.`name_ru-RU` title, `p`.`short_description_en-GB` text_en, c.category_id category_id
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id = p.product_id
WHERE p.product_publish=1";

    $db->setQuery($q);
    $res = $db->loadObjectList();

    $imp = "ООО `Мьюзик Ленд`";

    echo '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="' . date("Y-m-d h:i:s") . '">
<shop>
<name>piano.by</name><company>ООО «Мьюзик Ленд»</company><url>piano.by</url><currencies><currency id="BYN" rate="1"></currency></currencies><categories><category id="1">Товар</category></categories>

<delivery-options>
		<option cost="0" days="0" />
</delivery-options>

<offers>
';

    foreach($res AS $a)
    {
        // 0-Склад, 1-Под заказ(Д), 2-Нет, 3-Снято, 4-Под заказ
        $deliv = "";
        $available = "false";

        $deliv = "
		<delivery-options>
			<option cost=\"0\" days=\"\" />
		</delivery-options>
        ";

        switch($a->sklad*1)
        {
            case 0:
                $av_info = "";
                $available = "true";
                break;

            case 1:
                $av_info = "<sales_notes>Требуется предоплата</sales_notes>";
                $available = "true";
                break;
            case 4:
                $av_info = "<sales_notes>Требуется предоплата</sales_notes>";
                $available = "true";
                break;
            default:
                $av_info = "";
                break;
        }

        $w = "1";
        if( (strpos(" ".$a->title, "Casio")>0) || ((strpos(" ".$a->title, "Yamaha")>0)) )
            if( (strpos(" ".SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id, 1), "cifrovye-pianino")>0) ||
                (strpos(" ".SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id, 1), "sintezatory")>0) )
                $w="3";

        echo "<offer id=\"{$a->product_id}\" available=\"{$available}\">";
        echo "<url>https://piano.by" . SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id, 1) . "</url>";
        echo "<price>" . (round($a->product_price, 2)*$curr) . "</price>";

        if($a->product_old_price*1>0)
            echo "<oldprice>" . (round($a->product_old_price,2)*$curr) . "</oldprice>";

        echo "<delivery>true</delivery>";
        echo "<manufacturer_warranty>P{$w}Y</manufacturer_warranty>";
        echo $deliv;
        echo "<currencyId>BYN</currencyId>";
        echo "<categoryId>1</categoryId>";
        echo "<picture>https://piano.by/components/com_jshopping/files/img_products/{$a->image}</picture>";
        echo "<name>" . str_replace(Array("&", "<", ">", "'", '"'), " ", $a->title) . "</name>";
        echo "<description>" . str_replace(Array("&", "<", ">", "'", '"'), " ", $a->text_en) . "</description>";
        echo $av_info;
        echo "</offer>\n";
    }

    echo "</offers>
</shop>
</yml_catalog>";

    die;
}













// --- mail.ru retargeting
if(isset($_GET['mail_ru']))
{
    // --- Yandex Export YML:


//--- курс:
    //$db->setQuery("SELECT * FROM #__jshopping_currencies WHERE currency_id=2");
    //$res = $db->loadObject();
    //$curr = $res->currency_value*1;
    $curr = 1.0;
// 0-Склад, 1-Под заказ(Д), 2-Нет, 3-Снято, 4-Под заказ, 5-анонс
//    нужно выгрузить все цифровые пианино Casio, Yamaha, Korg, которые есть в наличии. А так же синтезаторы Casio и Yamaha, так же которые имеют статус - в наличии.

    $q =
        "
        SELECT
        p.product_ean, p.product_id, p.product_price, p.product_old_price, p.image, p.sklad, `p`.`name_ru-RU` title, c.category_id category_id, `cat`.`name_ru-RU` c_name, `cat`.`alias_ru-RU` c_alias,
        `cat_parent`.`alias_ru-RU` cat_parent_alias, `cat_parent`.`name_ru-RU` cat_parent_name, cat_parent.category_id cat_parent_id
        FROM #__jshopping_products AS p
        LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id = p.product_id
        LEFT JOIN #__jshopping_categories AS cat ON cat.category_id=c.category_id
        LEFT JOIN #__jshopping_categories AS cat_parent ON cat.category_parent_id=cat_parent.category_id
        WHERE
        p.sklad=0 AND p.product_publish=1 AND p.product_price>0
        AND (c.category_id=7 OR c.category_id=8 OR c.category_id=11 OR c.category_id=15 OR c.category_id=16)
        ";

    $db->setQuery($q);
    $res = $db->loadObjectList();


    $imp = "ООО `Мьюзик Ленд`";


    echo '<?xml version="1.0" encoding="utf-8"?>
<torg_price date="'. date("Y-m-d h:i:s") . '">
<shop>
<shopname>Салон музыкальных инструментов Piano.by</shopname>
<company>ООО `Мьюзик Ленд`</company>
<url>https://piano.by</url>
<currencies>
    <currency id="BYN" rate="1"/>
</currencies>
<categories>
    <category id="1" parentId="0">Цифровые пианино</category>
    <category id="2" parentId="0">Синтезаторы</category>
    <category id="7" parentId="1">Цифровые пианино Casio</category>
    <category id="8" parentId="1">Цифровые пианино Yamaha</category>
    <category id="11" parentId="1">Цифровые пианино Korg</category>
    <category id="15" parentId="2">Синтезаторы Casio</category>
    <category id="16" parentId="2">Синтезаторы Yamaha</category>
</categories>
<offers>
';

    foreach($res AS $a)
    {
        /*
                Description для цифровых пианино Casio и Yamaha -
            "N - официальная гарантия 2 года. Бесплатная доставка по всей Беларуси. Заходите."

        Description для цифровых пианино Korg -
            "N - официальная гарантия 1 год. Бесплатная доставка по всей Беларуси. Заходите."

        Description для синтезаторов Casio и Yamaha -
            "N - официальная гарантия 2 года. Быстрая доставка во все города РБ. Заходите."

        Где, вместо N - должно подставиться название товара http://joxi.ru/D2PDjDefdEDVo2
          */

        $desc = "";
        $local = "";
        switch ($a->category_id)
        {
            case 7:
                $desc = $a->title . " - официальная гарантия 3 года. Бесплатная доставка по всей Беларуси. Заходите.";
                $local = "<local_delivery_cost>0</local_delivery_cost>";
                break;
            case 8:
                $desc = $a->title . " - официальная гарантия 3 года. Бесплатная доставка по всей Беларуси. Заходите.";
                $local = "<local_delivery_cost>0</local_delivery_cost>";
                break;
            case 11:
                $desc = $a->title . " - официальная гарантия 1 год. Бесплатная доставка по всей Беларуси. Заходите.";
                $local = "<local_delivery_cost>0</local_delivery_cost>";
                break;
            case 15:
                $desc = $a->title . " - официальная гарантия 3 года. Быстрая доставка во все города РБ. Заходите.";
                break;
            case 16:
                $desc = $a->title . " - официальная гарантия 3 года. Быстрая доставка во все города РБ. Заходите.";
                break;
        }

        echo'
<offer id="'. $a->product_id . '" available="true" >
<url>https://piano.by' . SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id, 1) . '</url>
<price>' . (round($a->product_price, 2)*$curr) . '</price>
<oldprice>' . (round($a->product_old_price, 2)*$curr) . '</oldprice>
<currencyId>BYN</currencyId>
<categoryId>' . $a->category_id . '</categoryId>
<picture>https://piano.by/components/com_jshopping/files/img_products/' . $a->image . '</picture>
<typePrefix>' . $a->cat_parent_name . '</typePrefix>
<vendor>' . $a->c_name . '</vendor>
<model>' . $a->product_ean . '</model>
<description>' . $desc . '</description>
<delivery>true</delivery>
<pickup>true</pickup>
' . $local . '
</offer>
';
    }


    echo "
    </offers>
</shop>
</torg_price>";

    die;
}








if(isset($_GET['adtarget']))
{
    // --- adtarget Export XML:
    //--- курс:
    //$db->setQuery("SELECT * FROM #__jshopping_currencies WHERE currency_id=2");
    //$res = $db->loadObject();
    // $curr = $res->currency_value*1;
    $curr = 1.0;

// 0-Склад, 1-Под заказ(Д), 2-Нет, 3-Снято, 4-Под заказ, 5-анонс

    $q =
        "SELECT
p.*, `p`.`name_ru-RU` title, c.category_id category_id
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id = p.product_id
WHERE p.product_publish=1 AND p.product_price>1 AND p.sklad=0 AND (c.category_id NOT IN (64, 68, 67, 208, 62, 209, 223, 224)) ";
    // p.sklad<>2 AND p.sklad<>3 AND p.sklad<>5 AND

    $db->setQuery($q);
    $res = $db->loadObjectList();


    echo '<?xml version="1.0" encoding="UTF-8" ?>
<products>
';

    foreach($res AS $a)
    {
        // 0-Склад, 1-Под заказ(Д), 2-Нет, 3-Снято, 4-Под заказ
        if($a->sklad*1==0) // --- есть на складе
        {
            $available = "true";
            $av_info = "";
        }
        else
        {
            $available = "false";
            $av_info = "<sales_notes>Возможно потребуется предоплата.</sales_notes>";
        }

        $link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$a->category_id.'&product_id='.$a->product_id, 1);

        if($a->product_old_price>1)
            $old_price = "<old_price>".(round($a->product_old_price, 2)*$curr)."</old_price>";
        else
            $old_price = "";

        if(
            (strpos(" ".$link, 'cifrovye-pianino')>0) ||
            (strpos(" ".$link, 'sintezatory')>0) ||
            (strpos(" ".$link, 'akusticheskie-pianino')>0) ||
            (strpos(" ".$link, 'gitary')>0) ||
            (strpos(" ".$link, 'kombousiliteli')>0) ||
            (strpos(" ".$link, 'studijnye-monitory')>0) ||
            (strpos(" ".$link, 'aktivnye-akusticheskie-sistemy')>0) ||
            (strpos(" ".$link, 'mikrofony')>0) ||
            (strpos(" ".$link, 'mikshernye-pulty')>0)
        )
        {
            // --- уберем кавычки, апострафы и т.п.
            $a->title = str_replace(Array("&", "<", ">", "'", '"'), " ", $a->title);

            // --- сократим названия:
            $a->title = trim(str_replace(
                            Array(
"Цифровое пианино", "Электропиано", "Детское цифровое пианино", "Цифровой рояль",
"Синтезатор", "синтезатор", "MIDI-клавиатура", "Акустические пианино",  "Пианино акустическое",
"Акустический рояль", "Классическая гитара", "Гитара классическая", "Электроакустическая классическая гитара",
"Гитара акустическая", "Акустическая гитара", "Гитара электроакустическая",
"Электрогитара", "Бас-гитара", "Укулеле","Комбоусилители для клавишных", "Клавишный комбоусилитель", "Транзисторный комбоусилитель",
"Транзисторный комбоусилитель", "Гитарный комбоусилитель", "Гитарный комбоусилитель",
"Бас-гитарный комбоусилитель", "Активный студийный монитор", "Студийный монитор", "Акустическая система",
"Активная акустическая система", "Активные акустические системы", "Пара микрофонов", "Вокальная радиосистема",
"Головной микрофон", "Динамический вокальный микрофон", "Микрофон", "Радиосистема",
"USB-интерфейс", "Звуковая карта"
                            ),
                            "",
                            $a->title
                        ));


            echo
                "<product>
            <id>{$a->product_id}</id>
            <photo>https://piano.by/components/com_jshopping/files/img_products/{$a->image}</photo>
            <click>https://piano.by{$link}</click>
            <match>https://piano.by{$link}</match>
            <custom>
                <title>".$a->title."</title>
                <price>".(round($a->product_price, 2)*$curr)."</price>
                {$old_price}
            </custom>
            <special>false</special>
            </product>
            ";
        }
    }


    echo "</products>";

    die;
}



if(isset($_GET['z_back_forms']))
{
    $q = "SELECT * FROM #__z_back_forms ORDER BY id DESC";
    $db->setQuery($q);
    $res = $db->loadObjectList();
    echo "<table>";
    echo
    "
    <tr>
        <td style='min-width: 60px;'>ID</td>
        <td style='min-width: 130px;'>Тип</td>
        <td style='min-width: 130px;'>Имя</td>
        <td style='min-width: 130px;'>Телефон</td>
        <td style='max-width: 400px!important; overflow: hidden;'>Страница</td>
    </tr>";
    foreach($res AS $a)
    {
        echo
        "
        <tr>
            <td>{$a->id}</td>
            <td>{$a->form_type}</td>
            <td>{$a->user_name}</td>
            <td>{$a->user_phone}</td>
            <td>{$a->from_page}</td>
        </tr>
        ";
    }
    echo "<table>";
    die;
}