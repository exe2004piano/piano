<?php

if(isset($_GET['client']))
{
    $client = trim($_GET['client']);
}
else
{
    echo "Не указано имя";
    die;
}

if(isset($_GET['phone']))
{
    $phone = trim($_GET['phone']);
}
else
{
    echo "Не указан телефон";
    die;
}


if(isset($_GET['id']))
{
    $id = 1 * trim($_GET['id']);        // --- теперь ID точно число, SQL-инъекции не будет
}
else
{
    echo "Не указан товар";
    die;
}

if($id==0)
{
    echo "Не указан товар";
    die;
}


$roi_new = '';
if( (isset($_COOKIE['roi_new'])) && (trim($_COOKIE['roi_new'])!='') )
    $roi_new = "\nКод нашей статистики: " . $_COOKIE['roi_new'];

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

// --- id точно число, поэтому можно без плейсхолдера
$db->setQuery("SELECT `name_ru-RU` name FROM #__jshopping_products WHERE product_id={$id}");
if(!$item = $db->loadObject())
{
    echo "Указанного товара не существует";
    die;
}

// --- запишем действия для статистики:
$db->setQuery("UPDATE #__z_roistat_new SET z_analog=1, z_analog_text=" . $db->quote("Клиент: " . $client . " / Телефон: " . $phone . " / Подобрать аналог: " . $item->name) . " WHERE id=" . (1*$_COOKIE['roi_new']));
$db->execute();


$config = JFactory::getConfig();
$mails_analog = $config->get( 'mails_analog' );

@mail(
    $mails_analog,
    iconv("UTF-8", "windows-1251", "Заказ аналога с сайта piano.by"),
    iconv("UTF-8", "windows-1251", "Клиент: " . $client . "\nТелефон: " . $phone . "\nПодобрать аналог: " . $item->name . $roi_new)
);

echo "1";