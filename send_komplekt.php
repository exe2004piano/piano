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

$user_name = $_POST['user_name'];
$user_phone = $_POST['user_phone'];
$user_adr = $_POST['user_adr'];


$roi_new = '';
if( (isset($_COOKIE['roi_new'])) && (trim($_COOKIE['roi_new'])!='') )
    $roi_new = "\nКод нашей статистики: " . $_COOKIE['roi_new'];


$prod = 1*$_POST['prod'];
$komplekt = 1*$_POST['komplekt'];

$db->setQuery("SELECT * FROM #__jshopping_currencies WHERE currency_id=2");
$res = $db->loadObject();
$kurs = $res->currency_value;


$text = '';
$db->setQuery("SELECT `name_ru-RU` title, product_price p FROM #__jshopping_products WHERE product_id={$prod} ");
if(!$res = $db->loadObject())
{
    echo "0";
    die;
}


$prod_price = $res->p * $kurs;
$prod_title = $res->title;


$db->setQuery("SELECT k.*,
               p1.product_price price1, p2.product_price price2, p3.product_price price3, p4.product_price price4,
               p1.image image1, p2.image image2, p3.image image3, p4.image image4
               FROM #__z_komplekt AS k
               LEFT JOIN #__jshopping_products AS p1 ON p1.product_id = k.prod1
               LEFT JOIN #__jshopping_products AS p2 ON p2.product_id = k.prod2
               LEFT JOIN #__jshopping_products AS p3 ON p3.product_id = k.prod3
               LEFT JOIN #__jshopping_products AS p4 ON p4.product_id = k.prod4
               WHERE k.id={$komplekt}");

$res = $db->loadObject();
$summ = 0;
$summ += 1.0*$res->price1*$kurs;
$summ += 1.0*$res->price2*$kurs;
$summ += 1.0*$res->price3*$kurs;
$summ += 1.0*$res->price4*$kurs;

$summ_skidka = number_format($prod_price+floor($summ*(100-$res->skidka)/10000)*100, 0, " ", " ");
$summ_skidka_num = $prod_price+floor($summ*(100-$res->skidka)/10000)*100;
$komplekt_title = $res->title;

$text = "{$prod_title} + ({$komplekt_title}). Итоговая сумма со скидкой: {$summ_skidka}";


$db->setQuery("UPDATE #__z_roistat_new SET z_komplekt=1, z_komplekt_text=" . $db->quote($text) . ", z_summ={$summ_skidka_num} WHERE id=" . (1*$_COOKIE['roi_new']));
$db->execute();


$config = JFactory::getConfig();
$mails_analog = $config->get( 'mails_analog' );

@mail(
    $mails_analog,
    iconv("UTF-8", "windows-1251", "Заказ комплекта с сайта piano.by"),
    iconv("UTF-8", "windows-1251", "Клиент: " . $user_name . "\nТелефон: " . $user_phone . "\nАдрес доставки: " . $user_adr . "\nКомплект: " . $text . "\n" . $roi_new)
);

echo "1";