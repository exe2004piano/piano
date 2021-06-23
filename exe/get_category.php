<?php

$cat_id =  1*$_GET['id'];
if($cat_id==0)
    die;

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


$q = "
SELECT p.product_id , c.category_id, p.product_ean title, p.sklad, p.product_price, p.image, p.cz
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
LEFT JOIN #__jshopping_categories AS cat ON c.category_id=cat.category_id
WHERE p.product_publish = '1' AND cat.category_publish='1' AND c.category_id={$cat_id}
ORDER BY
    CASE
        WHEN p.sklad = 3 THEN 1 ELSE 0
    END ,
    p.product_price
";

if(strpos(" ".$_SERVER['SERVER_PROTOCOL'], 'HTTPS')>0)
    $protocol = 'https://';
else
    $protocol = 'http://';
$adr = $protocol . $_SERVER['SERVER_NAME'];


$db->setQuery($q);
if($res = $db->loadObjectList())
{
    $ids = "";
    unset($products);
    $i=0;
    foreach($res AS $a)
    {
        $ids .= $a->category_id . "|" . $a->product_id . "~";
        $products[$i]['title'] = $a->title;
        $products[$i]['cat_id'] = $a->category_id;
        $products[$i]['id'] = $a->product_id;
        $products[$i]['price'] = $a->product_price;
        $products[$i]['image'] = $a->image;
        $products[$i]['sklad'] = $a->sklad;
        $products[$i]['cz'] = $a->cz;
        $i++;
    }
    $itog = explode("~", file_get_contents($adr . '/?finder_links='.$ids));

    $i=0;
    $non_prod="";
    foreach($itog AS $link)
        if($link!='')
        {
            if($products[$i]['cz']*1==1)
                $products[$i]['price']=0;

            if( ($products[$i]['sklad']==3) && ($non_prod=="") )
            {   // --- нарвались на первый товар, снятый с производства:
                $non_prod = " class=\"none_prod\" ";
                echo "<br /><span style='font-size: 0.8em;'>Сняты с производства:</span><br />\n";
            }


            echo '<li class="product-'. $products[$i]['id'] . ' category" id="prod_link_'. $products[$i]['id'] . '" onmouseover="show_price('. $products[$i]['id'] . ','. $products[$i]['price'] . ');">
                        <a '. $non_prod . ' href="' . $link . '">
                                <span>'. $products[$i]['title'] . '</span>
                                <img class="parent_'. $products[$i]['cat_id'] . '" src="/components/com_jshopping/files/img_products/'. $products[$i]['image'] . '" alt="'. $products[$i]['title'] . '">
                        </a>
                  </li>';
            $i++;
        }
}
