<?php
defined('_JEXEC') or die;


if(strpos(" ".$_SERVER['REQUEST_URI'], "/index.php")>0)
{
    header("Location: /");
    die;
}

include_once (JPATH_ROOT.'/z/cron.php');

global $user;
global $is_manager;
global $roi_id;
global $roicode_products;

/*
// --- проверим онлайнер:
$ft = filemtime($_SERVER['DOCUMENT_ROOT']."/z/orders.onl");
if(time()-$ft>60)
{
    // --- прошло больше 1 минуты, можно опросить скрипт:
    @include_once($_SERVER['DOCUMENT_ROOT']."/z/test_onliner_cart.php");
}
*/

$user = JFactory::getUser();
$db = JFactory::getDbo();
$addr = trim($_SERVER['REQUEST_URI']);
if(strpos(" ".$addr, '?')>0)
    $addr = substr($addr, 0, strpos($addr, '?'));

$is_manager = 0;
// --- если зашли с рабочего компа, то менеджер
if($_SERVER['REMOTE_ADDR']=='213.184.241.76')
    $is_manager = 1;

if($is_manager==0)
{
// --- либо если залогинены под менеджеревским аккаунтом
    $q = "SELECT `value` FROM #__z_config WHERE id=21 OR id=22"; // --- тут список id менеджеров, которым будем показывать инфу о ценах конкурентов и прочее
    $db->setQuery($q);
    $res = $db->loadObjectList();


    foreach($res AS $r)
    {
        foreach(explode(",", $r->value) AS $v)
            if( ($v*1>0) && ($user->id==$v*1) )
                $is_manager = 1;
    }
}

global $valute;
$v_id = 1*$_COOKIE['currency'];
if(isset($_GET['prods_from_search_valute']))
    $v_id = $_GET['prods_from_search_valute']*1;

// --- если незадана валюта либо задана неверно, то установим по-умолчанию:
if( ($v_id<1) || ($v_id>3) )
    $v_id = 1;

// --- проверим на запросы экспорта прайсов и товаров:
include(dirname(__FILE__)."/exports.php");

?>


<!doctype html>
<html lang="ru-RU" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

  <jdoc:include type="head" />
  <meta name="yandex-verification" content="7ca87b3b49aae4a4" />
  <meta name="google-site-verification" content="joCW7KuOOuNtv4pEIsfpQTkwecJ6bVL-sTzqEIapgrQ" />
  <meta name="google-site-verification" content="a0qTQpPYwwWudRfB0WLPfncq2BMeymHyRiWkje6auEA" />

  <meta name="geo.placename" content="ул. Сурганова 57б, Минск, Беларусь" />
  <meta name="geo.position" content="53.9286880;27.5829580" />
  <meta name="geo.region" content="BY-" />
  <meta name="ICBM" content="53.9286880, 27.5829580" />

  <meta property="og:title" content="Магазин музыкальных инструментов" />
  <meta property="og:image" content="https://piano.by/templates/pianino_new/i/logo.png " />
  <meta property="og:description"
    content=" Пианино.by - первый музыкальный магазин клавишных инструментов с доставкой по все Беларусии! Смотрите на нашем сайте цены, отзывы, бесплатные консультации. " />
  <meta property="og:type" content="website" />
  <meta property="og:url" content=" https://piano.by/ " />
  <link rel="apple-touch-icon" sizes="57x57" href="/templates/pianino_new/icons/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/templates/pianino_new/icons/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/templates/pianino_new/icons/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/templates/pianino_new/icons/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/templates/pianino_new/icons/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/templates/pianino_new/icons/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/templates/pianino_new/icons/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/templates/pianino_new/icons/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/templates/pianino_new/icons/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192" href="/templates/pianino_new/icons/android-icon-192x192.png">
  <meta name="format-detection" content="telephone=no">
  <meta name="apple-mobile-web-app-capable" content="yes">

  <link rel="stylesheet" href="/templates/pianino_new/public/css/main.css"/>
</head>

<body>
  <div class="wrapper">
    <? include_once __DIR__.'/views/header.php'; ?>
    <? include_once __DIR__.'/views/main.php'; ?>
    <? include_once __DIR__.'/views/footer.php'; ?>
	  <? //include_once __DIR__.'/views/modals/modals.php'; ?>
  </div>

  <script src="/templates/pianino_new/public/js/app.min.js"></script>
</body>

</html>