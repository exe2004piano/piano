<?php

function error_load($text)
{
	echo $text;
	die;
}

error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

if ( (!isset($_POST['pass'])) || ($_POST['pass']!='SDFPOWERsdflkj43560923_sfjkzGFWRTzlkfjserSO3459') )
{
	echo "NO PASSWORD";
    die;
}

echo "Пароль найден - OK<br/>";

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

echo "Подключаем базу данных - OK<br/>";


if(isset($_FILES['onliner_info']))
{
	echo "Файл инфо загружен - OK<br/>";
    $from = $_FILES['onliner_info']['tmp_name'];
    $name = $_FILES['onliner_info']['name'];
    $file = "price/$name";
	@unlink($file);
	echo $file . "<br />";
    move_uploaded_file($from, $file);
	echo "Файл открыт для обработки - OK<br/>";

    require_once 'PHPExcel/IOFactory.php';
    if(!$reader = @PHPExcel_IOFactory::createReaderForFile($file))
        error_load('Error: file type error', $file);

    if(method_exists($reader,'getContiguous'))
        error_load('Error: file type error', $file);

    $reader->setReadDataOnly( true );

    try
    {
        if(!$excel =@$reader->load($file))
            error_load('Error: file type error');
    }
    catch(Exception $e)
    {
        error_load('Error: file type error');
    }

	echo "Файл загружен в память - OK<br/><hr />";
    $sheet = $excel->getSheet(0);


/*

$active->SetCellValue('A'.$i, "ID - НЕ МЕНЯТЬ!!!");
$active->SetCellValue('B'.$i, "Название");
$active->SetCellValue('C'.$i, "инфо");
остальное пофигу
 */


    foreach($sheet->getRowIterator() AS $row)
    {
        $id = 1*trim($sheet->getCell('A'.$row->getRowIndex()));
        if($id>0)
        {
			$info = $sheet->getCell('C'.$row->getRowIndex());
			$info = str_replace(Array("'", '"'), "`", $info);

            $q = "UPDATE #__jshopping_products SET z_info_onliner='{$info}' WHERE product_id={$id}";
            $db->setQuery($q);
            $db->execute();
        }
    }
    echo "Всё сделано!";
}
