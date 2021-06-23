<?php

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

$app = JFactory::getApplication('site');
$app->initialise();
$db = JFactory::getDbo();

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

$page_from = " ".$_SERVER['HTTP_REFERER'];
if(
	(!isset($_POST['user_name']))
 || (!isset($_POST['user_phone']))
 || (!isset($_POST['form_type']))
 || (trim($_POST['user_name'])=='')
 || (trim($_POST['user_phone'])=='')
 || (trim($_POST['form_type'])=='')
 || (trim($page_from)=='')
 || (strpos($page_from, 'pian')<1)
)
{
    // --- не заполнены 3 главных поля или нет реферера типа пианино - в сад
    header("location: ".$page_from."#error");
    die;
}


$_POST['user_name'] = str_replace(Array("'", '"', "<?", "$", "{", "}"), " ", $_POST['user_name']);
$_POST['user_phone'] = str_replace(Array("'", '"', "<?", "$", "{", "}"), " ", $_POST['user_phone']);


$uvedom = "";
$text = $subj = "";

global $crm_prods;
global $crm_touch;
global $site_id;

$crm_prods = "";
$crm_touch = 0;
$site_id = 1;
$retail = "";


switch($_POST['form_type'])
{
    case "form_callback":
        $subj = "Piano.by. Запрос обратного звонка";
        $text = "Данные клиента: " . $_POST['user_name'] . " , телефон: " . $_POST['user_phone'] . "<br />Отправлено со страницы: " . $page_from;
        $crm_touch = 9;
        break;


    case "form_anons" :
        if( (!isset($_POST['product_title'])) || (trim($_POST['product_title']=='')) )
        {
            // --- не заполнено необходимое поле - название товара
            header("location: ".$page_from);
            die;
        }
        $subj = "Piano.by. Клиент ждет поступления товара";
        $text = "Клиент ожидает поступление анонсированной модели на склад: ". $_POST['product_title'] . "<br />\nДанные клиента: " . $_POST['user_name'] . " , телефон/email: " . $_POST['user_phone'] . "<br />Отправлено со страницы: " . $page_from;
        $crm_touch = 15;
        break;


    case "form_analog" :
        if( (!isset($_POST['product_title'])) || (trim($_POST['product_title']=='')) )
        {
            // --- не заполнено необходимое поле - название товара
            header("location: ".$page_from);
            die;
        }
        $subj = "Piano.by. Нужен аналог инструмента";
        $text = "Клиент просит подобрать аналог: ". $_POST['product_title'] . "<br />\nДанные клиента: " . $_POST['user_name'] . " , телефон/email: " . $_POST['user_phone'] . "<br />Отправлено со страницы: " . $page_from;
        if(trim($_POST['user_comment'])!='')
            $text = "({$_POST['user_comment']}). ".$text;

        $crm_touch = 7;
        break;


    case "form_oneclick" : case "form_express" :
        if( (!isset($_POST['product_title'])) || (trim($_POST['product_title']=='')) )
        {
            // --- не заполнено необходимое поле - название товара
            header("location: ".$page_from);
            die;
        }
        $subj = "Piano.by. Купить быстро ";
        if($_POST['form_type']=='form_express')
			$subj = "Piano.by. Экспресс-доставка ";
        $text = $subj . $_POST['product_title'] . "<br />\nДанные клиента: " . $_POST['user_name'] . " , телефон/email: " . $_POST['user_phone'] . "<br />Отправлено со страницы: " . $page_from;
        $crm_touch = 5;
        break;


    case "form_cheap" :
        if( (!isset($_POST['product_title'])) || (trim($_POST['product_title']=='')) )
        {
            // --- не заполнено необходимое поле - название товара
            header("location: ".$page_from);
            die;
        }
        $subj = "Piano.by. Хочу дешевле!";
        $text =
            "Клиент просит скидку на товар: ". $_POST['product_title'] .
            "<br />\nДешевле нашел в магазине: " . $_POST['user_url'] .
            "<br />\nСтоимость: " . $_POST['user_price'] .
            "<br />\nДанные клиента: " . $_POST['user_name'] . " , телефон: " . $_POST['user_phone'] . ", email: " . $_POST['user_email'] .
            "<br />\nКомментарий: " . $_POST['user_comment'] .
            "<br />Отправлено со страницы: " . $page_from;
        $crm_touch = 8;
        break;

    case "form_credit" :
        if( (!isset($_POST['product_title'])) || (trim($_POST['product_title']=='')) )
        {
            // --- не заполнено необходимое поле - название товара
            header("location: ".$page_from);
            die;
        }
        $subj = "Piano.by. Оформить товар в кредит";
        $text =
            "Купить в кредит: ". $_POST['product_title'] .
            "<br />\nПервоначальный взнос: " . $_POST['user_price'] .
            "<br />\nСрок платежа: " . $_POST['user_srok'] . " мес." .
            "<br />\nДанные клиента: " . $_POST['user_name'] . " , телефон/email: " . $_POST['user_phone'] .
            "<br />Отправлено со страницы: " . $page_from;
        $crm_touch = 16;
        break;


    case "form_price_lower" :
        if( (!isset($_POST['product_title'])) || (trim($_POST['product_title']=='')) )
        {
            // --- не заполнено необходимое поле - название товара
            header("location: ".$page_from);
            die;
        }
        $subj = "Piano.by. Запрос о снижении цены";
        $text =
            "Клиент ждёт, когда подешевеет товар: ". $_POST['product_title'] .
            "<br />\nДанные клиента: " . $_POST['user_name'] . " , телефон/email: " . $_POST['user_phone'] .
            "<br />Отправлено со страницы: " . $page_from;
        $crm_touch = 8;
        break;

    case "form_rassrochka" :
        if( (!isset($_POST['product_title'])) || (trim($_POST['product_title']=='')) )
        {
            // --- не заполнено необходимое поле - название товара
            header("location: ".$page_from);
            die;
        }
        $subj = "Piano.by. Запрос рассрочки";
        $text =
            "Клиент хочет рассрочку на товар: ". $_POST['product_title'] .
            "<br />\nДанные клиента: " . $_POST['user_name'] . " , телефон/email: " . $_POST['user_phone'] .
            "<br />Отправлено со страницы: " . $page_from;
        $crm_touch = 20;
        break;
}


    // PROD|KOMP*KOLVO=
if(strpos("  ".$_POST['pcmr_id'], "|")<1)
	$crm_prods = (1*trim($_POST['pcmr_id'])).'|0*1=';
else
{
    $crm_touch = 6;
	$crm_prods = trim($_POST['pcmr_id']).'*1=';
}

global $res;
include_once(JPATH_ROOT."/z/sync/send.php");
$res = 1*$res;

if($subj=='')
{
    // --- что-то пошло не так
    header("location: ".$page_from);
    die;
}

/*
$uvedom = $subj . "\n";
$uvedom .= "Данные клиента: \n" . $_POST['user_name'] . "\nтелефон: " . $_POST['user_phone'] . "\nОтправлено со страницы: \n" . $page_from;
$uvedom .= "\n-----------------------------------\n";

$uvedom_all = '';
if(file_exists(JPATH_ROOT . '/z/'.date("ymd").'.txt'))
{
    $uvedom_all = file_get_contents(JPATH_ROOT . '/z/'.date("ymd").'.txt');
}
$uvedom_all = $uvedom . $uvedom_all;
file_put_contents(JPATH_ROOT . '/z/'.date("ymd").'.txt', $uvedom_all);
*/

// --- запишем данные в базу
$zakaz = new stdClass();
$zakaz->user_name = $_POST['user_name'];
$zakaz->user_phone = $_POST['user_phone'];
$zakaz->user_price = $_POST['user_price'];
$zakaz->user_srok = $_POST['user_srok'];
$zakaz->user_url = $_POST['user_url'];
$zakaz->user_comment = $_POST['user_comment'];
$zakaz->form_type = $_POST['form_type'];
$zakaz->from_page = $page_from;
$zakaz->product_title = $_POST['product_title'];


$result = $db->insertObject('#__z_back_forms', $zakaz);
$id = $db->insertid();

$text .= "<br />\n<br />\nДанные внесены в базу с ID = {$id}";

$mainframe = JFactory::getApplication();
$mailfrom = $mainframe->getCfg('mailfrom');
$fromname = $mainframe->getCfg('fromname');
$mailer = JFactory::getMailer();
$mailer->CharSet = 'utf-8';
$mailer->setSender(array($mailfrom, $fromname));
$mailer->addRecipient(explode(',',$jshopConfig->contact_email));
$mailer->setSubject($subj);
$mailer->setBody($text);
$mailer->isHTML(true);
$send = $mailer->Send();

// header("location: ".$page_from."#thanks");
?>


<head>
    <script>
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
</head>
<body>


    <script>
        <?
        if( ($_POST['form_type']=='form_oneclick') || ($_POST['form_type']=='form_rassrochka') )
        {
            ?>
            (window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function() {
                rrApi.order({
                    transaction: <?php echo $res; ?>,
                    items: [
                        {
                            id: <?=$_POST['pcmr_id']*1;?>,
                            qnt: 1,
                            price: <?=$_POST['pcmr_price']*1.0; ?>
                        }
                    ]
                });
            } )
            <?
        }
        ?>
        setTimeout(function(){location.href="<?=$page_from."#thanks";?>";}, 50);
    </script>

</body>
