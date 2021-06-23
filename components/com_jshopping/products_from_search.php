<?php


defined('_JEXEC') or die;
$db = JFactory::getDBO();

$link = $_GET['atrib'];

$db->setQuery("SELECT * FROM #__z_products_from_search WHERE link=".$db->quote($link));
if(!$a = $db->loadObject())
{
    return "ERROR";
}

$url = $a->search_link;
if(strpos($url, '?')<1)
    $url .= '?p=p';
$url .= '&prods_from_search=1';

global $valute;
$v_id = 1*$_COOKIE['currency'];
// --- если незадана валюта либо задана неверно, то установим по-умолчанию:
if( ($v_id<1) || ($v_id>3) )
    $v_id = 1;

$url .= "&prods_from_search_valute=".$v_id;

$text1 = "";

$url = str_replace('pianino.by', 'piano.by', $url);
$url = 'https://piano.by/'.$url;
$url = str_replace('https://piano.by/', '/', $url);
$url = str_replace('//', '/', $url);
$url = $_SERVER['HTTP_HOST'].'/'.$url;

$text = file_get_contents_ssl($url);

$text = substr($text, strpos($text, 'cat_id" value="')+strlen('cat_id" value="'));




$cat_id = substr($text, 0, strpos($text, '"'));

$text = substr($text, strpos($text, 'class="section__row"'));
$text = substr($text, 0, strpos($text, '</section>'));

$text = '<input type="hidden" id="cat_id" value="'.$cat_id.'" /><div ' . $text;

if(strpos($text, 'b-text__contentWrap')>0)
{
    $text = substr($text, 0, strpos($text, '<div class="b-text__contentWrap'));
}

$text = str_replace('prods_from_search', 'prods_from_search_paginator', $text);
$text = str_replace('href="//', 'href="/', $text);

$db->setQuery("SELECT `name_ru-RU` name FROM #__jshopping_categories WHERE category_id=".(1*$cat_id));
$cat = $db->loadObject();

$bread =
    '<div class="b-breadcrumbs">
        <ul class="b-breadcrumbs__list">
            <li class="b-breadcrumbs__item display-only">
                <a class="b-breadcrumbs__link" href="/">Главная</a>
            </li>
            <li class="b-breadcrumbs__item">
                <a href="/goods/'.$a->link.'" class="b-breadcrumbs__link">'.$cat->name.'</a>
        </li>
	</ul>
</div>';


$text = $bread . $a->text_1 . $text . "<br />" . $a->text_2 . "<br /><br />";
$doc = JFactory::getDocument();
$doc->setTitle($a->title);
$doc->setDescription($a->description);

return $text;

/*
$temp = substr($text, strpos($text, 'b-filter'));
$temp = substr($temp, 0, strpos($temp, '</form>'));

$temp = '<form class="' . $temp . '</form>';

$text1 = substr($text, strpos($text, '<nav class="b-item__content">'));
$text1 = substr($text1, 0, strpos($text1, '</nav>')+strlen('</nav>'));

if(strpos($text, '<nav class="b-section__pagination">')>0)
{
    $text = substr($text, strpos($text, '<nav class="b-section__pagination">'));
    $text = substr($text, 0, strpos($text, '</nav>')+strlen('</nav>'));
    $text1 .= $text;
}


$bread =
'<div class="b-breadcrumbs">
    <ul class="b-breadcrumbs__list">
        <li class="b-breadcrumbs__item display-only">
            <a class="b-breadcrumbs__link" href="/">Главная</a>
        </li>
        <li class="b-breadcrumbs__item">
            <a href="/goods/'.$a->link.'" class="b-breadcrumbs__link">'.$a->title.'</a>
        </li>
	</ul>
</div>';


$text1 = $bread . "<br />" . $a->text_1 . "<br />" . $text1 . "<br />" . $a->text_2;
$doc = JFactory::getDocument();
$doc->setTitle($a->title);
$doc->setDescription($a->description);

$text1 = '<div class="col-sm-3 b-filter__static">' . $temp . '</div><div class="col-sm-9">' . $text1 . '</div>';
return $text1;
*/