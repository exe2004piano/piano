<?php


if(!isset($_COOKIE['new_basket']))
{
    header('Location: /');
    die;
}



// отправка заказа с нового пианино
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

	$z_user = get_current_user_z();

global $valute;

$db = JFactory::getDBO();
if (!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php'))
{
    JError::raiseError(500,"Please install component joomshopping");
}

jimport('joomla.application.component.model');

require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php');
require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');
JSFactory::loadCssFiles();
JSFactory::loadLanguageFile();
$jshopConfig = JSFactory::getConfig();

JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_jshopping/models');

/*

DD - день, две цифры
MM - месяц, две цифры
YY - год, две цифры
HH - час заказа, две цифры
NM - минуты заказа, две цифры
SS - секунды заказа, две цифры

 */


// например: 2809161435 * 100
$code = date("dmyHi")*100;
while(true)
{
    $db->setQuery("SELECT id FROM #__z_orders WHERE code={$code}");
    if($db->loadObject())
    {
        // пока существует запись с таким кодом, увеличим его на 1 и повторим
        $code++;
        continue;
    }
    // --- нашли "свободный" код - выходим из цикла
    break;
}



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

if(!$items)
{
    header('Location: /');
    die;
}




$promo = $promo_items = $promo_cats = $promo_sklad = null;
$teacher_id = 0;
try {
// --- если промокод еще не был зафиксирован, но передан нам в пост-запросе:
    if (isset($_SESSION['promocode']) && $_SESSION['promocode']!='')
    {
        $promocode = trim($_SESSION['promocode']);

        $db->setQuery("SELECT * FROM #__z_promo WHERE promo=".$db->quote($promocode));
        $promo=$db->loadObject();

        if($promo)
        {
			$teacher_id = $promo->parent_id;
            // --- промокод найден, проверим его на годность:
            $start_time = strtotime($promo->start_time);
            $end_time = strtotime($promo->end_time);
            $time = time();

            $promo_items = explode("~", $promo->products_id);
            $promo_cats = explode("~", $promo->categories_id);
            $promo_sklad = explode("~", $promo->sklad);

            // --- проверим временные ограничения:
            if(($end_time>0) && ($time>$end_time))
            {
                $promo_text = str_replace('<!--promo_info-->', 'Действие данного промокода завершено<br /><br />', $promo_text);
                throw new Exception();
            }

            if(($start_time>0)&&($time<$start_time))
            {
                $promo_text = str_replace('<!--promo_info-->', 'Действие данного промокода еще не началось<br /><br />', $promo_text);
                throw new Exception();
            }



            // --- до сюда можем дойти только если все проверки по промокоду пройдены
            $_SESSION['promocode'] = $promocode;

            $promo_text = 'Применен промокод: <b>'.$promocode.'</b><br />';



        }
        else
        {
            $promo_text = str_replace('<!--promo_info-->', 'Введеный промокод не существует<br /><br />', $promo_text);
            $error=1;
        }
    }
}
catch (Exception $e) {
    unset($_SESSION['promocode']);
    // выход из проверок если промокод не состоялся
}




$text = "";
$baza_text = "";
$i=1;


global $crm_prods;
global $crm_touch;
global $site_id;

$crm_prods = "";
$crm_prices = "";
$crm_touch = 18;
$site_id = 1;
$google_gp = "";
$retail_gp = "";
$summ = 0;
$promo_info_text = '';

foreach($items AS $key=>$value)
{
    $num += $value;
    $key = 1*$key;
    $q = "
    SELECT  p.`name_ru-RU` title, p.product_ean, p.`alias_ru-RU` alias, p.product_price, p.price_reg, p.product_old_price, p.image,
            cat.`alias_ru-RU` cat_alias, cat_parent.`alias_ru-RU` cat_parent_alias, c.category_id, p.product_id, p.sklad,
            cat.`name_ru-RU` cat_name, cat_parent.`name_ru-RU` cat_parent_name,
            m.`name_ru-RU` m_name
    FROM #__jshopping_products p
    LEFT JOIN #__jshopping_products_to_categories AS c USING (product_id)
    LEFT JOIN #__jshopping_categories AS cat ON c.category_id=cat.category_id
    LEFT JOIN #__jshopping_categories AS cat_parent ON cat.category_parent_id=cat_parent.category_id
    LEFT JOIN #__jshopping_manufacturers AS m ON m.manufacturer_id=p.product_manufacturer_id
    WHERE p.product_id={$key}
    ";

    $db->setQuery($q);
    $res = $db->loadObject();
    $skidka_za_reg = "";
	$res->is_promo = false;
	if($z_user)
	{
		if($z_user && $res->product_price > $res->price_reg)
		{
			$skidka_za_reg = "Скидка за регистрацию";
			$res->product_price = $res->price_reg;
			$res->is_promo = true;
		}
	}



    $google_gp .= "
{
'name': '".str_replace(Array("'", '"'), '`', $res->title)."',
'id': '".$key."',
'price': '".$res->product_price."',
'brand': '".$res->m_name."',
'category': '".str_replace(Array("'", '"'), '`', $res->cat_parent_name.'/'.$res->cat_name)."',
'quantity': ".$value."
},
";


    $retail_gp .= "
    {
        id: {$key},
        qnt: {$value},
        price: {$res->product_price}
    },
    ";

    $promo_info_text = '';
    if($z_user)
		$promo_info_text .= $skidka_za_reg;


    // проверим на принадлежность к промокоду по набору ограничений если они есть:
    if(isset($_SESSION['promocode']))
    {
        // --- считаем что изначально для данного товара ниодно из условий промокода не выполнено
        $in_sklad = $in_summ = $in_product = 0;

        // --- если есть ограничение по складу
        if(sizeof($promo_sklad)<=1)
            // --- ограничений на принадлежность по складу не установлена, значит помечаем что всё ок
            $in_sklad = 1;
        else
            if(in_array($res->sklad, $promo_sklad))
                $in_sklad = 1;


        // --- если нет ограничения по стоимости либо оно подходит под наш товар
        if($promo->min_price<=$res->product_price)
            $in_summ = 1;


        // --- теперь проверим есть ли ограничения по ID товара либо ID категории:
        if ( (sizeof($promo_cats)<=1) && (sizeof($promo_items)<=1) )
        {
            // --- ограничений на принадлежность к категории или товарам нет:
            $in_product = 1;
        }
        else
        {
            // --- если ограничение есть, то либо товар в списке либо его категория в списке:
            if (
                (in_array($res->product_id, $promo_items))
                ||
                (in_array($res->category_id, $promo_cats))
            )
                $in_product = 1;
        }

    }

    $is_promo = $in_sklad * $in_product * $in_summ;


    if($is_promo && !$res->is_promo)
    {
        // --- товар попал под промокод
        if($promo->proc_skidka>0)
        {
            $res->product_price = round($res->product_price * (1-$promo->proc_skidka/100), 2);
            $promo_info_text .= '<br />Скидка '.$promo->proc_skidka.'%<br /> по промокоду';
        }

        if($promo->fix_skidka>0)
        {
            $res->product_price = $res->product_price - $promo->fix_skidka;
            $promo_info_text .= '<br />Скидка '.echo_price($promo->fix_skidka).'<br /> по промокоду';
        }

        if($promo_info_text=='')
            $promo_info_text .= '<br />'.$promo->info;
    }

	// PROD|KOMP*KOLVO=
	$crm_prods .= $key.'|0*'.$value.'p'.round($res->product_price,2).'=';


	$pre_summ = $value*$res->product_price;
    $summ += $pre_summ;
    $old_price='';

    $sklad_title = '';
    $sklad_status = '';

    switch($res->sklad)
    {
        case '0' :
            $sklad_title = 'в наличии';
            break;
        case '1' :
            $sklad_title = 'на складе';
            break;
        case '2' :
            $sklad_title = 'нет в наличии';
            break;
        case '3' :
            $sklad_title = 'снят с производства';
            break;
        case '4' :
            $sklad_title = 'под заказ';
            break;
        case '5' :
            $sklad_title = 'анонсируемая модель';
            break;
    }


    $pre_summ = echo_price($res->product_price * $value, 1);
    $res->product_price = echo_price($res->product_price, 1);

    $pre_summ_ye = echo_price($res->product_price * $value, 2);
    $res->product_price_ye = echo_price($res->product_price, 2);

    $pre_summ_ru = echo_price($res->product_price * $value, 3);
    $res->product_price_ru = echo_price($res->product_price, 3);


    $text .=
        "
        <tr>
            <td>{$i}</td>
            <td><img src='http://piano.by/components/com_jshopping/files/img_products/thumb_{$res->image}' alt=''></td>
            <td>{$res->title}</td>
            <td>{$res->product_price} ( {$res->product_price_ru} , {$res->product_price_ye} )</td>
            <td>{$value}</td>
            <td>{$pre_summ} ( {$pre_summ_ru} , {$pre_summ_ye} ) <small>{$promo_info_text}</small></td>
            <td>{$sklad_title}</td>
        </tr>";

    $baza_text .= "{$res->product_id} // {$res->title} // {$res->product_price} // {$value} // {$pre_summ} // {$sklad_title} ||\n";

    $i++;
}

if($text=='')
{
    header('Location: /');
    die;
}

$promo_text = '';
if(isset($promo))
{
    $promo_text = '<h5>Применен промокод: ' . $promo->promo.'</h5>';
}

$text = "

<style>
.basket_table
{
    border-collapse: collapse;
}

.basket_table td
{
    padding: 3px 10px;
    border: 1px solid #BBB;
}

div
{
    width: 1200px;
    padding: 10px;
    border: 1px solid #BBB;
}

small
{
    color: red;
    font-weight:bold;
}

h5
{
    color: red;
    font-size: 1.5em;
}
</style>

<h4>Информация о заказе:</h4>
{$promo_text}
<table class='basket_table'>
        <tr>
            <td style='width: 30px;'>№</td>
            <td style='width: 50px;'>Фото</td>
            <td style='width: 450px;'>Название</td>
            <td style='width: 100px;'>Цена</td>
            <td style='width: 50px;'>Кол-во</td>
            <td style='width: 100px;'>Сумма</td>
            <td style='width: 100px;'>Склад</td>
        </tr>" .
        $text .
"</table>";

$summ1 = echo_price($summ, 1);
$summ1_ye = echo_price($summ, 2);
$summ1_ru = echo_price($summ, 3);

$time = "с " . str_replace("_", " до ", $_POST['filterSelect']);


$oplata = (int)$_POST['oplata_type'];
switch ($oplata)
{
    case 1:
		$_POST['user_comment'] .= "\nОплата: Наличными при получении";
		break;
	case 2:
		$_POST['user_comment'] .= "\nОплата: Через терминал или наличными в салоне";
		break;
	case 3:
		$_POST['user_comment'] .= "\nОплата: Картой через интернет";
		break;
}





$text .= "
<br />
<h4>Общая сумма заказа: {$summ1} ( {$summ1_ru} , {$summ1_ye} ) {$skidka}</h4>
<br />
Текущие курсы (цену в бел.руб. ДЕЛИМ на курс:
<br />

USD: " . number_format($valute[2]['value'], 2, ".", " ") . "<br />
RUR: " . number_format($valute[3]['value'], 2, ".", " ") . "<br />

<h4>Информация клиента:</h4>
<table class='basket_table'>
    <tr>
        <td><b>Имя</b></td>
        <td>{$_POST['user_name']}</td>
    </tr>
    <tr>
        <td><b>Телефон</b></td>
        <td>{$_POST['user_phone']}</td>
    </tr>


    <tr>
        <td><b>Email</b></td>
        <td>{$_POST['user_mail']}</td>
    </tr>

    <tr>
        <td><b>Город</b></td>
        <td>{$_POST['user_city']}</td>
    </tr>

    <tr>
        <td><b>Адрес</b></td>
        <td>{$_POST['user_street']}</td>
    </tr>

    <tr>
        <td><b>Комментарий</b></td>
        <td>{$_POST['user_comment']}</td>
    </tr>

    <tr>
        <td><b>Дата / время</b></td>
        <td>{$_POST['user_date']} {$time}</td>
    </tr>

    <tr>
        <td><b>Код заказа</b></td>
        <td>{$code}</td>
    </tr>


</table>
";

$text =
"<center>
<div>
<img src='http://piano.by/templates/pianino/images/s5_logo.png' />
"
. $text
. "
</div>
</center>";




$mailer = JFactory::getMailer();
$mailer->setSender("order@piano.by");
// $email = array("executer2004@mail.ru", "piano.berillo@gmail.com");
// $mailer->addRecipient($email);
$mailer->addRecipient(explode(',',$jshopConfig->contact_email));
$mailer->isHTML(true);
$mailer->setSubject('piano.by Заказ');
$mailer->setBody($text);
$mailer->Send();


// --- запишем данные в базу
$zakaz = new stdClass();
$zakaz->user_name = $_POST['user_name'];
$zakaz->user_phone = $_POST['user_phone'];
$zakaz->user_mail = $_POST['user_mail'];
$zakaz->user_adr = "{$_POST['user_city']} // {$_POST['user_street']}";


if($_POST['dostavka_type']*1==2)
{
    $_POST['user_comment'] .= "\n\n\nСамовывоз из салона!";
}

$zakaz->user_comment = $_POST['user_comment'];

$zakaz->data_time = "{$_POST['user_date']} {$time}";
$zakaz->basket = $baza_text;
$zakaz->summ = $summ;
$zakaz->promo = $promocode;
$zakaz->code = $code;
$zakaz->promo_info = $promo_info;
$result = $db->insertObject('#__z_orders', $zakaz);
$id = $db->insertid();

$_POST['u_time'] = $time;
$_POST['promo_code'] = $promocode;
$_POST['teacher_id'] = $teacher_id;

global $res;
$zakaz_id = include_once(JPATH_ROOT."/z/sync/send.php");
$res = (int)$res;


setcookie('new_basket','',null,'/');

$google_gp = substr($google_gp, 0, strrpos($google_gp, ','));

$google_gp_1 = "
dataLayer.push({
    'event': 'checkout',
    'ecommerce': {
      'checkout': {
            'actionField': {'step': 1, },
            'products': [" . $google_gp . "]
      }
    }
});
";

$google_gp =
"
dataLayer = [{
    'ecommerce': {
        'purchase': {
            'actionField': {
                'id': '".(1*$res)."',
                'affiliation': 'Online Store',
                'revenue': '".$summ."',
      },
      'products': [".$google_gp . "]
    }
  }
}];
";


$retail_gp =
"

(window[\"rrApiOnReady\"] = window[\"rrApiOnReady\"] || []).push(function() {
    rrApi.order({ 
        transaction: ".(1*$res).",
        items: [
                {$retail_gp} 
               ]
    });
} )

";


?>


<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-P9S78GL');</script>
    <!-- End Google Tag Manager -->


    <script type="text/javascript">
        var rrPartnerId = "5d230f6297a52807203cc359";
        var rrApi = {};
        var rrApiOnReady = rrApiOnReady || [];
        rrApi.addToBasket = rrApi.order = rrApi.categoryView = rrApi.view =
            rrApi.recomMouseDown = rrApi.recomAddToCart = function() {};
        (function(d) {
            var ref = d.getElementsByTagName('script')[0];
            var apiJs, apiJsId = 'rrApi-jssdk';
            if (d.getElementById(apiJsId)) return;
            apiJs = d.createElement('script');
            apiJs.id = apiJsId;
            apiJs.async = true;
            apiJs.src = "//cdn.retailrocket.ru/content/javascript/tracking.js";
            ref.parentNode.insertBefore(apiJs, ref);
        }(document));
    </script>

<?
	$_SESSION['order_z_id'] = $id;
    $_SESSION['order_summ'] = $summ;
    $_SESSION['order_id'] = $res;
    $_SESSION['oplata'] = $oplata;
    $_SESSION['name'] = $_POST['user_name'];
    $_SESSION['phone'] = $_POST['user_phone'];
	$_SESSION['adr'] = isset($_POST['user_city'])?("{$_POST['user_city']} / {$_POST['user_street']}"):'';
	$_SESSION['promocode']='';
?>
<script>
    <?=$google_gp;?>
    function do_it()
    {
        location.href='/basket-step3?zakaz=<?=$res;?>&summ=<?=$summ;?>&oplata=<?=$oplata;?>';
    }
    setTimeout(do_it(), 200);
</script>


</head>

<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P9S78GL";
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<script>
    <?=$retail_gp;?>
</script>

</body>