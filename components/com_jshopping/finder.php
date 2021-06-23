<?php

error_reporting(0);
// --- нам приходит запрос, который нужно обработать
// --- результаты вернем в виде первых 10 найденных товаров (название-фото-описание-цена), если запрос пришел по аяксу
// --- либо полным списком с пагинацией, если запрос пришел со страницы [FINDER]


// --- запрос на поиск получим через GET-параметр word, так же и в случае обращения через джумлу



$ajax = 0;
if($_SERVER['SCRIPT_NAME']=='/components/com_jshopping/finder.php')
{
    // --- нам пришел запрос по аяксу, значит для начала подрубим джумлу для дальнейшей работы с фреймворком:
    $ajax = 1;

    define('_JEXEC', 1);
    define('DS', DIRECTORY_SEPARATOR);
    if (file_exists(dirname(__FILE__) . '/defines.php')) {
        include_once dirname(__FILE__) . '/defines.php';
    }
    if (!defined('_JDEFINES')) {
        define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
        require_once JPATH_BASE.'/includes/defines.php';
    }
    require_once JPATH_BASE.'/includes/framework.php';
    JFactory::getApplication('site')->initialise();
    $db = JFactory::getDbo();

    $app = JFactory::getApplication('site');
    $app->initialise();
    if (!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php')){
        JError::raiseError(500,"Please install component \"joomshopping\"");
    }
    require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php');
    require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');

    $jshopConfig = JSFactory::getConfig();
    $jshopConfig->cur_lang = $jshopConfig->frontend_lang;

}

// --- нам пришел запрос из джумлы:
defined('_JEXEC') or die;
$db = JFactory::getDbo();

if(!function_exists('translit'))
{
    function translit($string)
    {
        $string = trim(preg_replace("/[^а-яА-Яa-zA-Z0-9]/u"," ",$string));
        $string = str_replace("  ", " ", $string);
        $string = str_replace("  ", " ", $string);
        $string = str_replace("  ", " ", $string);
        $string = str_replace("  ", " ", $string);
        $string = str_replace("  ", " ", $string);
        $string = str_replace("  ", " ", $string);
        $string = str_replace("  ", " ", $string);
        $string = str_replace("  ", " ", $string);
        $string = str_replace("  ", " ", $string);
        $string = str_replace("  ", " ", $string);

        $replace=array(
            "&#039;"=>"-",
            "&#8203;"=>"-",
            "&quot;"=>"-",
            "&mdash;"=>"-",
            "&nbsp;"=>" ",
            "&amp;"=>"-",
            "…"=>"-",
            "&"=>"-",
            "_"=>"-",
            "#"=>"-",
            ";"=>"-",
            "["=>"-",
            "]"=>"-",
            ")"=>"-",
            "("=>"-",
            "—"=>"-",
            "№"=>"-",
            "+"=>"-",
            "·"=>"",
            "
            "=>"",
            "$"=>"-",
            "-"=>"-",
            "%"=>"-",
            "`"=>"",
            "'"=>"-",
            "«"=>"",
            "»"=>"",
            ","=>"",
            " "=>"-",
            "/"=>"-",
            '\\'=>"",
            "\""=>"",
            "="=>"",
            "?"=>"",
            "."=>"",
            "!"=>"",
            ":"=>"",
            ","=>"",
            "а"=>"a","А"=>"a",
            "б"=>"b","Б"=>"b",
            "в"=>"v","В"=>"v",
            "г"=>"g","Г"=>"g",
            "д"=>"d","Д"=>"d",
            "е"=>"e","Е"=>"e",
            "ё"=>"e","Ё"=>"e",
            "ж"=>"zh","Ж"=>"zh",
            "з"=>"z","З"=>"z",
            "и"=>"i","И"=>"i",
            "й"=>"y","Й"=>"y",
            "к"=>"k","К"=>"k",
            "л"=>"l","Л"=>"l",
            "м"=>"m","М"=>"m",
            "н"=>"n","Н"=>"n",
            "о"=>"o","О"=>"o",
            "п"=>"p","П"=>"p",
            "р"=>"r","Р"=>"r",
            "с"=>"s","С"=>"s",
            "т"=>"t","Т"=>"t",
            "у"=>"u","У"=>"u",
            "ф"=>"f","Ф"=>"f",
            "х"=>"h","Х"=>"h",
            "ц"=>"c","Ц"=>"c",
            "ч"=>"ch","Ч"=>"ch",
            "ш"=>"sh","Ш"=>"sh",
            "щ"=>"sch","Щ"=>"sch",
            "ъ"=>"","Ъ"=>"",
            "ы"=>"y","Ы"=>"y",
            "ь"=>"","Ь"=>"",
            "э"=>"e","Э"=>"e",
            "ю"=>"yu","Ю"=>"yu",
            "я"=>"ya","Я"=>"ya",
            "і"=>"i","І"=>"i",
            "ї"=>"yi","Ї"=>"yi",
            "є"=>"e","Є"=>"e"
        );

        $string = str_replace(array("'",'"'),"", $string);
        $str=iconv("UTF-8","UTF-8//IGNORE",strtr($string,$replace));
        $str = strtolower(preg_replace("/[^a-zA-Z0-9]/u","-",$str));
        return $str;
    }
}


global $all_extra_names;
global $all_extra;

$db->setQuery("SELECT id, field_id, `name_ru-RU` title FROM #__jshopping_products_extra_field_values ORDER BY field_id, ordering");
$res = $db->loadObjectList();
foreach($res AS $e)
    $all_extra[$e->field_id][$e->id]=$e->title;

$db->setQuery("SELECT id, `name_ru-RU` title FROM #__jshopping_products_extra_fields ORDER BY id");
$res = $db->loadObjectList();
foreach($res AS $e)
    $all_extra_names[$e->id]=$e->title;
unset($res);


// ajax=1 при прямом запросе
$word = trim($_GET['word']);

if(mb_strlen($word, "utf-8")<3)
{
    if($ajax==1)
    {
        die;
    }
    else
        return "Задан слишком короткий запрос для поиска";
}


$word_orig = $word;

// --- найдем замены слов и сделаем их в виде массива:
$q = "SELECT * FROM #__z_config WHERE `name`='SEARCH' ";
$db->setQuery($q);
$res = $db->loadObject();


$temp = explode ("\n", $res->value);
unset($zamena);
foreach($temp AS $t)
{
    $i = explode("=", $t);
    $old = explode(",", $i[1]);
    foreach($old AS $o)
    {
        $o = trim($o);
        if ( ($o!="") && (trim($i[0])!="") )
            $zamena[$o] = " ".trim($i[0])." ";
    }
}


$new_word = strtr($word, $zamena); // --- получаем строку с новым набором слов после автозамены, но могут быть лишние пробелы

$new_word = str_replace(
    Array("_", ",", "=", "/", "+", "!", "?", "@", "#", "$", "%", "^", "&", "*", "(", ")", "'", '"', "`"),
    " ",
    $new_word
);


$new_word = trim($new_word);
if($new_word=="")
{
    echo "Зарос слишком короткий";
    die;
    return "Зарос слишком короткий";
}

$words = explode(" ", $new_word);
$q = "";


$q_name = "";
$q_not_name = "";
$q_desc = "";
$q_not_desc = "";
$q_short_desc = "";

foreach($words AS $w)
    if(trim($w)!='')
    {
        $w = $db->quote("%".$w."%");
        // --- W - каждое отдельное слово, по нему и будем искать совпадения
        // --- совпадать должны все слова либо в названии (заголовке русском), либо в описании (русском)
        $q_name .= " AND p.`name_ru-RU` LIKE " . $w;
        $q_desc .= " AND p.`description_ru-RU` LIKE " . $w;
        $q_short_desc .= " AND p.`short_description_en-GB` LIKE " . $w;
    }



if(trim($q_name)!="")
{
    $q_name = " p.product_publish=1 " . $q_name;
    $q_desc = " p.product_publish=1 " . $q_desc;
    $q_short_desc = " p.product_publish=1 " . $q_short_desc;
}
else
{
    echo "Зарос слишком короткий";
    die;
    return "Зарос слишком короткий";
}


$start = 1*$_GET['start'];
$limit = 12;


$q =
    "
SELECT SQL_CALC_FOUND_ROWS * FROM
(
SELECT  p.`name_ru-RU` title, p.`short_description_en-GB` info,
c.category_id, p.*,
(100) AS num
FROM #__jshopping_products p
LEFT JOIN #__jshopping_products_to_categories AS c USING (product_id)
LEFT JOIN #__jshopping_categories AS cat USING (category_id)
WHERE {$q_name} AND cat.category_publish=1


UNION ALL


SELECT p.`name_ru-RU` title, p.`short_description_en-GB` info,
c.category_id, p.*,
(200) AS num
FROM #__jshopping_products p
LEFT JOIN #__jshopping_products_to_categories AS c USING (product_id)
LEFT JOIN #__jshopping_categories AS cat USING (category_id)
WHERE {$q_desc} AND cat.category_publish=1


UNION ALL


SELECT p.`name_ru-RU` title, p.`short_description_en-GB` info,
c.category_id, p.*,
(300) AS num
FROM #__jshopping_products p
LEFT JOIN #__jshopping_products_to_categories AS c USING (product_id)
LEFT JOIN #__jshopping_categories AS cat USING (category_id)
WHERE {$q_short_desc} AND cat.category_publish=1
)

SUBQ GROUP BY product_id

ORDER BY num,
CASE
WHEN sklad=3 THEN 100
		WHEN sklad=2 THEN 90
		WHEN sklad=5 THEN 85
		WHEN sklad=4 THEN 80
		WHEN sklad=1 THEN 70
		ELSE 0
END


LIMIT {$start}, $limit

";




$db->setQuery($q);
if($items = $db->loadObjectList())
{
    $q = "SELECT FOUND_ROWS() num";
    $db->setQuery($q);
    $num = $db->loadObject();
// $num->num - общее количество строк

    $kurs = 0;
    if($ajax==0)
    {
//        jimport( 'joomla.html.pagination' );
//        $pag = new JPagination( $num->num, $start, $limit);
//        $pages = $pag->getPagesLinks();


        $currency = JTable::getInstance('currency', 'jshop');
        $currency->load(2);
        $this->kurs = $currency->currency_value;
    }
    else
    {
        $q = "SELECT currency_value FROM #__jshopping_currencies WHERE currency_id=2";
        $db->setQuery($q);
        $k = $db->loadObject();
        $kurs = $k->currency_value;
    }

    $text = "";

    if($ajax==0)
    {
        foreach($items AS $product)
        {
            $this->product = $product;
            $item = include(JPATH_BASE."/components/com_jshopping/exe_product.php");
            $text .= $item ;
        }

        $text =
        '<nav class="b-item__content">
            <ul class="b-item__contentList" data-screen="screenItem" id="products_ul">'.
            $text .
            '</ul>
         </nav>';


        if($num->num>$limit)
        {

            // $num->num  --- всего найдено
            // $start --- текущий старт
            // $limit  --- сколько на странице

            $pages =
            '<nav class="b-section__pagination">
                <ul class="pagination">';

            $cur_page = $start/$limit;
            $all_pages = $num->num/$limit;

            for($p=0;$p<$all_pages;$p++)
            {
                $active = "";
                if($p==$cur_page)
                    $active = ' class="active" ';
                $pages .=
                    '<li'.$active.'><a href="/search?word='.$_GET['word'].'&start='.($p*$limit) . '">' . ($p+1) . '</a></li>';
            }


            $pages .= '</ul></nav>';
        }
        return $text . $pages;
    }
    else
    {
        $text .= '<ul class="form-popup__list">';
        $w_like = "";
        $w_like_w = "";

        $ww = explode(" ", $_GET['word']);

        foreach($ww AS $w)
        {
            if(trim($w)=='')
                continue;

            $w_like .= " AND (c.find_sound LIKE '%".soundex(trim(translit($w)))."%') ";
            $w_like_w .= " AND (c.`name_ru-RU` LIKE '%".(trim($w))."%') ";
        }


        if($w_like!="")
        {
            $w_like = " ( (1=1) ".$w_like.") ";
            $w_like_w = " ( (1=1) ".$w_like_w.") ";

            $w_q =  "
            SELECT
              c.category_id, c.`name_ru-RU` title, c.`alias_ru-RU`
            , c1.category_id c1_id, c1.`name_ru-RU` c1_title, c1.`alias_ru-RU` c1_alias
            , c2.category_id c2_id, c2.`name_ru-RU` c2_title, c2.`alias_ru-RU` c2_alias
            , c3.category_id c3_id, c3.`name_ru-RU` c3_title, c3.`alias_ru-RU` c3_alias
            FROM #__jshopping_categories AS c
            LEFT JOIN #__jshopping_categories AS c1 ON c1.category_id=c.category_parent_id
            LEFT JOIN #__jshopping_categories AS c2 ON c2.category_id=c1.category_parent_id
            LEFT JOIN #__jshopping_categories AS c3 ON c3.category_id=c2.category_parent_id
            WHERE ({$w_like} OR {$w_like_w})
            LIMIT 3
            "; // AND c.category_parent_id>0

            $db->setQuery($w_q);
            if($w_like_all = $db->loadObjectList())
            {
                $text.= "<li class=\"form-popup__top\" > <span>Найдено в категориях:</span> <div class=\"form-popup__links\">";
                
                foreach($w_like_all AS $w)
                {
                    $title = trim($q->c3_title . " " . $w->c2_title . " " . $w->c1_title . " " . $w->title);
                    $link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$w->category_id);
                    $link = str_replace('/components/com_jshopping', '', $link);
                    $text .= "<a href='{$link}'>{$title}</a>";
                }
                $text .= "</div></li>";
            }
        }


        $k=0;
        foreach($items AS $product)
        {
            $sklad = "";
            $sklad_class = "none";

            if($product->product_price*1>0)
                $price = echo_price($product->product_price, -1, -1, $product);
            else
                $price = "Цена по запросу";

            switch ($product->sklad)
            {
                case (0) :
                    $sklad = "В наличии!";
                    $sklad_class = "inStock";
                    break;
                case (1) :
                    $sklad = "На складе";
                    $sklad_class = "inStock";
                    break;
                case (4) :
                    $sklad = "Под заказ";
                    $sklad_class = "order";
                    break;
                case (2) :
                    $sklad = "<small>Нет на складе, <u>есть аналог!</u></small>";
                    $sklad_class = "none";
                    $price = "";
                    break;
                case (3) :
                    $sklad = "<small>Снят с производства, <u>есть аналог!</u></small>";
                    $sklad_class = "none";
                    $price = "";
                    break;
                case (5) :
                    $sklad = "<small>Анонсируемая <br> модель</small>";
                    $sklad_class = "notify";
                    break;
            }

            $info = trim(mb_substr($product->info, 0, 60, "utf-8"));
            if($info!="")
                $info .= "...";


            $link = $product->real_link;
            $k++;

            $text .= "
<li class=\"form-popup__item\">
    <a href=\"{$link}\" class=\"form-popup__link\">
    <div class=\"form-popup__wrap\">
        <div class=\"form-popup__img\">
            <img src=\"/components/com_jshopping/files/img_products/thumb_{$product->image}\" alt=\"{$product->title}\">
        </div>

        <div class=\"form-popup__contains\">
            <div class=\"form-popup__title\">
                <h4>{$product->title}</h4>
            </div>
            <span class=\"form-popup__text\">{$price}</span>
           
        </div>
         <span class=\"form-popup__status is--{$sklad_class}\">{$sklad}</span>
    </div>
        

    </a>
</li>
";
        }

        $text .= '</ul>';

        $text .=
            "<div class=\"form-popup__footer\" >
                <span>Всего найдено <span>{$num->num}</span> товаров</span>
                <div class=\"form-popup__btn\">
                    <a href=\"/search?word={$_GET['word']}\" class=\"bv-btn\"><span class=\"bv-btn__text\">Посмотреть</span></a>
                </div>
            </div>";


        echo $text;
    }

}
else
{
    $q = "DELETE FROM #__z_search_stat WHERE time > NOW() - INTERVAL 2 SECOND AND ip=" . $db->quote($_SERVER['REMOTE_ADDR']);
    $db->setQuery($q);
    $db->execute();

    // --- запишем стату:
    $q = "INSERT INTO #__z_search_stat (word_orig, word, found_rows, ip, time) VALUES (" .
        $db->quote($word_orig) . ", " .
        $db->quote($new_word) . ", " .
        0 . ", " .
        $db->quote($_SERVER['REMOTE_ADDR']) . ", " .
        "now()  )";
    $db->setQuery($q);
    $db->execute();


    // echo "==={$word_orig}==={$new_word}----";



$text .= "

<div class='row'>
<div class='col-md-4'>
	<img src='/images/not_found.jpg' style='border-radius: 50%;' />
</div>

<div class='col-md-8'>
<h3>По Вашему запросу товаров не найдено :( </h3><br />
Попробуйте изменить запрос, или же, свяжитесь с нашими консультантами<br />
по телефону +375 44 7500500 или через форму заявки.<br />
Они помогут найти/подобрать то что нужно Вам.<br />  <br />
<!--        <script src='https://www.google.com/recaptcha/api.js'></script>  -->

        <form action='/z/not_found.php' method='post'>
            <div class='row'>
                <div class='col-sm-2'>Как Вас зовут:</div>
                <div class='col-sm-2'><input type='text' name='name' required /></div>
                <br /><br />
            </div>

            <div class='row'>
                <div class='col-sm-2'>Ваш телефон:</div>
                <div class='col-sm-2'><input type='text' name='phone' required ></div>
                <br /><br />
            </div>

            <div class='row'>
                <div class='col-sm-2'>E-mail:</div>
                <div class='col-sm-2'><input type='text' name='email' required ></div>
                <br /><br />
            </div>

            <div class='row'>
                <div class='col-sm-2'>Что Вы искали:</div>
                <div class='col-sm-2'><input type='text' name='not_found' readonly value='" . $_GET['word'] . "'></div>
                <br /><br />
            </div>
<!--
            <div class='row'>
                <div class='col-sm-2'><br /><br />Антиспам:</div>
                <div class='col-sm-2'><div class='g-recaptcha' data-sitekey='6LceOAsUAAAAADDaGBdojjHmEuPlQj1FJSncZj3K'></div></div>
                <br /><br />
            </div>
-->

            <div class='row'>
                <div class='col-sm-2'><input type='submit' class='btn btn-info' value='Отправить' /></div>
            </div>
        </form>
</div>
</div>

        ";

    return $text;
}