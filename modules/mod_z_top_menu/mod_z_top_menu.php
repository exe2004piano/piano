<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$db = JFactory::getDbo();

$menutype = trim($params->get('menutype'));
$db->setQuery("SELECT title, path, params FROM `ronck_menu` WHERE menutype=" . $db->quote($menutype) . " AND published=1 ORDER BY lft");
$all = $db->loadObjectList();
$addr = trim($_SERVER['REQUEST_URI']);

echo '<ul class="nav__list">';

foreach($all AS $a)
{
	$p = json_decode($a->params);
    $a->path = str_replace('home', '', $a->path);
    echo "<li class=\" ". $p->{'menu-anchor_css'}." nav__item\"><a href=\"/{$a->path}\" class=\"nav__link\">{$a->title}</a></li>\n";

}
?>
</ul>