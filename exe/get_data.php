<?php
die;

$info = trim($_POST['res']);
$info = str_replace(Array('"', "'"), " ", $info);


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

$db = JFactory::getDBO();


// --- пришел тик от клиента, значит он сейчас онлайн
// --- найдем в базе его по roi_stat и дополним значение текущим временем и данными res если они не пустые
$roi = 1*trim($_COOKIE['roi_new']);
$q = "SELECT * FROM #__z_roistat_new WHERE id={$roi}";
$db->setQuery($q);
if($user = $db->loadObject())
{
    // --- нашли юзера, запишем данные
    $last_phone = $user->last_phone;
    // --- если введенный номер отличается от того, что ранее вносил юзер - то запишем его в базу
    if( ($info!='') && (strpos("  ".$last_phone, $info)<1) )
    {
        if(strpos("  ".$info, $last_phone)<1)
        {
            $info .= " / " . $last_phone;
        }

            $qq = ", last_phone=" . $db->quote($info);
    }


    $q = "UPDATE #__z_roistat_new SET last_page=" . $db->quote($_SERVER['HTTP_REFERER']) . ", last_time=" . time() . $qq . " WHERE id={$user->id}";
    $db->setQuery($q);
    $db->execute();
}