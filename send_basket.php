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

if (!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php')){
    JError::raiseError(500,"Please install component \"joomshopping\"");
}

jimport('joomla.application.component.model');

require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php');
require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');
JSFactory::loadCssFiles();
JSFactory::loadLanguageFile();
$jshopConfig = JSFactory::getConfig();

JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_jshopping/models');

$cart = JModelLegacy::getInstance('cart', 'jshop');
$cart->load("cart");

$currency = JTable::getInstance('currency', 'jshop');
$currency->load(2);
$kurs = $currency->currency_value;

// print_r($cart); die;

$count = 0;
$text = "";
$summ = 0;
$prod_id = 0;
$prod_price = 0;
$prod_name = "";

foreach($cart->products AS $a)
{
    $prod_id = $a['product_id'];
    $prod_price = $a['price']*$kurs;
    $prod_name = $a['product_name'];

    $text .=
        "<tr>
        <td>{$a['product_name']}</td>
        <td>&nbsp;&nbsp;</td>
        <td>" . number_format($a['price']*$kurs, 0, " ", " ") . "</td>
        <td>&nbsp;&nbsp;</td>
        <td>{$a['quantity']}</td>
        <td>&nbsp;&nbsp;</td>
        <td>" . number_format($a['price']*$a['quantity']*$kurs, 0, " ", " ") . "</td>
    </tr>\n";
    $count++;
    $summ += ($a['price']*$a['quantity']*$kurs);
}

if($count>0)
{

    if($_POST['basket_user_komplekt']*1>0)
    {
        // --- у нас заказан комплект
        $text = "";
        // --- получим инфу о всех товарах из данного комплекта:
        $db->setQuery("SELECT k.*,
                           p1.product_price price1, p2.product_price price2, p3.product_price price3, p4.product_price price4,
                           p1.`name_ru-RU` name1, p2.`name_ru-RU` name2, p3.`name_ru-RU` name3, p4.`name_ru-RU` name4
                           FROM #__z_komplekt AS k
                           LEFT JOIN #__jshopping_products AS p1 ON p1.product_id = k.prod1
                           LEFT JOIN #__jshopping_products AS p2 ON p2.product_id = k.prod2
                           LEFT JOIN #__jshopping_products AS p3 ON p3.product_id = k.prod3
                           LEFT JOIN #__jshopping_products AS p4 ON p4.product_id = k.prod4
                           WHERE id=" . ($_POST['basket_user_komplekt']*1));
        $res = $db->loadObject();

        $kom = "";
        $summ = 0;

        // --- каждый из товаров 1,2,3,4 обработаем:
        if($res->prod1*1>0)
        {
            $summ += (1.0*$res->price1*$kurs);
            $kom .= "+ " . $res->name1;
        }

        if($res->prod2*1>0)
        {
            $summ += (1.0*$res->price2*$kurs);
            $kom .= "+ " . $res->name2;
        }

        if($res->prod3*1>0)
        {
            $summ += (1.0*$res->price3*$kurs);
            $kom .= "+ " . $res->name3;
        }

        if($res->prod4*1>0)
        {
            $summ += (1.0*$res->price4*$kurs);
            $kom .= "+ " . $res->name4;
        }

        $summ = $prod_price+floor($summ*(100-$res->skidka)/10000)*100;
        // //--- получим инфу о всех товарах из данного комплекта


        $text =
        "<tr>
        <td>Комплект: {$prod_name} {$kom} </td>
        <td>&nbsp;&nbsp;</td>
        <td>" . number_format($summ, 0, " ", " ") . "</td>
        <td>&nbsp;&nbsp;</td>
        <td>{$a['quantity']}</td>
        <td>&nbsp;&nbsp;</td>
        <td>" . number_format($summ*$a['quantity'], 0, " ", " ") . "</td>
        </tr>\n";

        $summ = $summ*$a['quantity'];
    }






    $text = "<table>
    <tr>
        <th style='width: 300px;'>Наименование</th>
        <th>&nbsp;&nbsp;</th>
        <th style='width: 100px;'>Цена</th>
        <th>&nbsp;&nbsp;</th>
        <th style='width: 30px;'>Шт.</th>
        <th>&nbsp;&nbsp;</th>
        <th style='width: 100px;'>Стоимость</th>
    </tr>\n" .
        $text .
        "</table><br />\n";

    $text .= "Итоговая стоимость заказа: " . number_format($summ, 0, " ", " ") . "<br />\n";
    $text .= "<br />Данные по доставке:<br />\n";
    $text .= "ФИО: " . $_POST['basket_user_name'] . "<br />\n";
    $text .= "Телефон: " . $_POST['basket_user_phone'] . "<br />\n";
    $text .= "Адрес: " . $_POST['basket_user_adr'] . "<br />\n";
    $text .= "Комментарий: " . $_POST['basket_user_comment'] . "<br />\n";
    $text .= "Email: " . $_POST['basket_user_email'] . "<br />\n";

    $mainframe = JFactory::getApplication();
    $mailfrom = $mainframe->getCfg('mailfrom');
    $fromname = $mainframe->getCfg('fromname');




    //message client
    $subject = "Piano.By Order";

    if(trim($_POST['basket_user_email'])!='')
    {
        $mailer = JFactory::getMailer();
        $mailer->CharSet = 'utf-8';
        $mailer->setSender(array($mailfrom, $fromname));
        $mailer->addRecipient(trim($_POST['basket_user_email']));
        $mailer->setSubject($subject);
        $mailer->setBody($text);
        $mailer->isHTML(true);
        $send = $mailer->Send();
    }


//    $text .= "\n<br />ROISTAT: " . $_COOKIE['roistat_visit'];

    if( (isset($_COOKIE['roi_new'])) && (trim($_COOKIE['roi_new'])!='') )
    {
        $text .= "\n<br />Stat code: " . $_COOKIE['roi_new'] . "\n<br />";
        $db->setQuery( "SELECT cur_page, z_istok FROM #__z_roistat_new WHERE id=" . (1*$_COOKIE['roi_new']) );
        $res = $db->loadObject();
        if($res->cur_page!='')
        {
            $temp = explode("~~~", $res->cur_page);
            $temp = array_unique($temp);
            $text .= "Источник перехода: " . $res->z_istok . "<br /><br />\nПосещенные страницы: <br />\n";
            foreach($temp AS $t)
                $text .= "https://piano.by" . trim($t) . "<br />\n";
        }
    }

    if($_POST['basket_user_komplekt']*1<=0)
    {       // --- просто корзина:
        $db->setQuery("UPDATE #__z_roistat_new SET z_basket=1, z_basket_text=" . $db->quote($text) . ", z_summ={$summ} WHERE id=" . (1*$_COOKIE['roi_new']));
        $db->execute();
    }
    else
    {   // --- комплект:
        $db->setQuery("UPDATE #__z_roistat_new SET z_komplekt=1, z_komplekt_text=" . $db->quote($text) . ", z_summ={$summ} WHERE id=" . (1*$_COOKIE['roi_new']));
        $db->execute();
    }




    $mailer = JFactory::getMailer();
    $mailer->CharSet = 'utf-8';
    $mailer->setSender(array($mailfrom, $fromname));
    $mailer->addRecipient(explode(',',$jshopConfig->contact_email));
    $mailer->setSubject($subject);
    $mailer->setBody($text);
    $mailer->isHTML(true);
    $send = $mailer->Send();

    $cart->clear();
    echo "1";
}
else
{
    echo "0";
}



