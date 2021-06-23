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

$text1 = "";
$text = file_get_contents($url);
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

return $text1;
