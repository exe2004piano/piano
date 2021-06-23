<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$db = JFactory::getDbo();

$menutype = $params->get('menutype');
$menutitle = $module->title;
$db->setQuery("SELECT * FROM #__menu WHERE menutype='{$menutype}' AND published=1 AND access=1 ORDER BY lft");
$res = $db->loadObjectList();

echo'
<nav class="b-footer__listWrap">
    <h3 class="b-footer__title">' . $menutitle . '</h3>
        <ul class="b-footer__list">
        ';

foreach($res AS $a)
{

    if($a->type=='alias')
    {
        $par = json_decode($a->params);
        $q = "SELECT * FROM #__menu WHERE published=1 AND access=1 AND id={$par->aliasoptions}";
        $db->setQuery($q);
        $a = $db->loadObject();
    }


    echo
    '<li class="b-footer__item">
        <a href="/' .$a->path . '" class="b-footer__link">'. $a->title . '</a>
    </li>';
}

echo
    '</ul>
</nav>';




