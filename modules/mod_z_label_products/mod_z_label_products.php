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


?>


<section class="b-product">
  <div class="container">
    <div class="b-tab" data-tab-wrap>

      <?php
    $db->setQuery("SELECT * FROM #__jshopping_product_labels WHERE id IN (" . ($params->get('ids')) . ")");
    $labels = $db->loadObjectList();
    $header = '<ul class="b-tab__list">';
    $body = '<div class="b-tab__contentWrap">';
    $active = 'active';
    $i = 1;
    foreach($labels AS $label)
        {
            $header .=
            '<li class="b-tab__item">
                <a href="#" class="b-tab__link '.$active.'" data-tab="' . $i . '">' . $label->name . '</a>
            </li>';


            $db->setQuery("
            SELECT p.`name_ru-RU` title, p.product_id, p.sklad, p.product_price, p.product_old_price, p.product_ean, p.image, p.average_rating, c.category_id, p.label_id
            FROM #__jshopping_products AS p
            LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
            WHERE p.label_id={$label->id} AND p.product_publish=1 AND p.product_price>0
            LIMIT 0, 10
            ");
            if($products = $db->loadObjectList())
            {
                $body .=
                    '
                    <div class="b-tab__content '.$active.'" data-tabContent="' . $i . '">
                            <div class="list slick-carousel">
                    ';


                $body .= include(JPATH_ROOT.'/components/com_jshopping/get_label_products.php');


                $body .=
                    '
                    </div>
                </div>
                    ';
            }



            $i++;
            $active = '';
        }

    $body .= '</div>';
    $header .= '</ul>';

    echo $header;
    echo $body;

?>
    </div>
  </div>
</section>