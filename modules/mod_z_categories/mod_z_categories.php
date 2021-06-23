<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$db = JFactory::getDBO();


$all_attribs = null;
$db->setQuery("SELECT * FROM #__jshopping_free_attr ORDER BY id");
$res = $db->loadObjectList();
foreach($res AS $e)
{
    $all_attribs[$e->id] = $e;
}
unset($res);
$attr_link = 'name_en-GB';
$attr_name = 'name_ru-RU';


$all_cats = null;
$q = "SELECT `name_ru-RU` title, category_id FROM #__jshopping_categories ";
$db->setQuery($q);
$res = $db->loadObjectList();
foreach($res AS $e)
{
    $all_cats[$e->category_id] = $e->title;
}
unset($res);


?>

<section class="b-delivery">
    <div class="container">
        <ul class="b-delivery__list">
            <?php
            for($i=1;$i<=20;$i++)
            {
                if(trim($params->get("cat_{$i}_title"))!='')
                {
                ?>

                    <li class="b-delivery__item">
                        <div class="b-delivery__card">
                            <div class="b-delivery__img">
                                <div class="lazyload">
                                    <!--<img src="<?=get_webp('/'.$params->get("cat_{$i}_img")); ?>" alt="<?php echo $params->get("cat_{$i}_title"); ?>">-->
                                </div>
                            </div>
                            <div class="b-delivery__content">
                                <h3 class="b-delivery__title"><?php echo $params->get("cat_{$i}_title"); ?></h3>
                                <ul class="b-delivery__productList">
                                    <?php
                                    $temp = explode(",", trim(no_tags($params->get("cat_{$i}_ids"))));
                                    foreach($temp AS $t)
                                    {

                                        if(strpos(' '.$t, '[')>0)
                                        {
                                            // --- атрибуты:
                                            $t = 1*trim(str_replace(Array('[', ']'), '', $t));

                                            echo '<li class="b-delivery__productItem">
                                                        <a href="/items/'.$all_attribs[$t]->$attr_link.'" class="b-delivery__productLink">'.$all_attribs[$t]->$attr_name.'</a>
                                                </li>';
                                        }
                                        elseif($t*1>0)
                                        {
                                            $t = $t*1;
                                            // --- категория:
                                            $link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$t);
                                            $link = str_replace('http://', 'https://', $link);

                                            echo '<li class="b-delivery__productItem">
                                                        <a href="'.$link.'" class="b-delivery__productLink">'.$all_cats[$t].'</a>
                                                </li>';
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </li>

                <?php
                }
            }

            ?>
        </ul>
    </div>
</section>
