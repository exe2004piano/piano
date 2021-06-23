<?php

// Array ( [item_vopros] => Цифровое пианино Casio CDP-220 R [name_vopros] => укеукекуе [phone_vopros] => 345345 )

if(
    (isset($_POST['item_vopros'])) &&
    (trim($_POST['item_vopros'])!='') &&
    (isset($_POST['name_vopros'])) &&
    (trim($_POST['name_vopros'])!='') &&
    (isset($_POST['phone_vopros'])) &&
    (trim($_POST['phone_vopros'])!='')
)
{
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

	$item_price = "";
	if(isset($_POST['item_price']))
		$item_price = "Цена : " . $_POST['item_price'] . "\n";

    $roi_new = '';
    if( (isset($_COOKIE['roi_new'])) && (trim($_COOKIE['roi_new'])!='') )
        $roi_new = "\nКод нашей статистики: " . $_COOKIE['roi_new'];

    $text =
        "Нужна консультация: \n" .
        "Товар: " . $_POST['item_vopros'] . "\n" .
        $item_price .
        "Клиент: " . $_POST['name_vopros'] . "\n" .
        "Телефон: " . $_POST['phone_vopros'] . "\n";


    if(1*$_COOKIE['roi_new']>0)
    {
        $db = JFactory::getDBO();
        $db->setQuery("UPDATE #__z_roistat_new SET z_callback=1, z_callback_text=" . $db->quote($text) . " WHERE id=" . (1*$_COOKIE['roi_new']));
        $db->execute();
    }

    $config = JFactory::getConfig();
    $mails_analog = $config->get( 'mails_analog' );

    @mail(
        $mails_analog,
        iconv("UTF-8", "windows-1251", "Клиент запрашивает консультацию с сайта piano.by"),
        iconv("UTF-8", "windows-1251", $text . $roi_new;)
    );

}

echo
"<script>
    location.href='/thanks';
</script>";
