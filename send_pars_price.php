<?php

function error_load($text)
{
	echo $text;
	die;
}


if ( (!isset($_POST['pass'])) || ($_POST['pass']!='SDFPOWERsdflkj43560923_sfjkzGFWRTzlkfjserSO3459') )
{
	echo "NO PASSWORD";
    die;
}



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

echo "Пароль найден - OK<br/>";
echo "Подключаем базу данных - OK<br/>";

//--- курс:
//$db->setQuery("SELECT * FROM #__jshopping_currencies WHERE currency_id=2");
//$res = $db->loadObject();
//$curr = $res->currency_value*1;
$curr  = 1.0;

if(isset($_FILES['all_price']))
{
	echo "Файл прайса загружен - OK<br/>";
    $from = $_FILES['all_price']['tmp_name'];
    $name = $_FILES['all_price']['name'];
    $file = "price/$name";
	@unlink($file);
	echo $file . "<br />";
    move_uploaded_file($from, $file);
	echo "Файл прайса открыт для обработки - OK<br/>";

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

	echo "Прайс загружен в память - OK<br/><hr />";
    $sheet = $excel->getSheet(0);


/*

$active->SetCellValue('A'.$i, "ID - НЕ МЕНЯТЬ!!!");
$active->SetCellValue('B'.$i, "Название");
$active->SetCellValue('C'.$i, "цена");
остальное пофигу
 */


    foreach($sheet->getRowIterator() AS $row)
    {
        $id = 1*trim($sheet->getCell('A'.$row->getRowIndex()));
        if($id>0)
        {
			$p = $sheet->getCell('C'.$row->getRowIndex());
			$p = str_replace(",", ".", $p);
            $price = 1.0*trim($p) / $curr;  // --- цена в уе
            echo $sheet->getCell('B'.$row->getRowIndex()) . " = " . $price . "<br />\n";

            $q = "UPDATE #__jshopping_products SET product_price={$price} WHERE product_id={$id}";
            $db->setQuery($q);
            $db->execute();
        }
    }
    echo "Всё сделано!";
}
