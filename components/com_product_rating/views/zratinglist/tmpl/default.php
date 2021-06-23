<?php // no direct access
defined('_JEXEC') or die('Restricted access');
$main_link = JRoute::_( "index.php?option=com_product_rating");
foreach($this->data as $a)
{
    $link = $main_link.'/'.$a->alias;
    echo "<a href='{$link}'>{$a->h1}</a><br />";
}
