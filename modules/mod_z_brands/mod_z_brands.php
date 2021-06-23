<?php
defined('_JEXEC') or die;
$db = JFactory::getDbo();
?>

<section class="b-section__brend">
    <div class="container">
        <div class="b-section__title">
            <span><?php echo $module->title;?></span>
            <a href="/brand">Смотреть все</a>
        </div>
        <div class="b-section__slider slick-carousel">
            <?php

            // --- найдем ID брендов, которые нужно выводить
            $db->setQuery("SELECT value FROM #__z_config WHERE `name`='popular_brands' ");
            $ids = $db->loadObject();

            $db->setQuery("SELECT manufacturer_logo, `name_ru-RU` title FROM #__jshopping_manufacturers WHERE manufacturer_publish=1 AND manufacturer_id IN ({$ids->value}) ORDER BY manufacturer_id");
            $res = $db->loadObjectList();
            foreach($res AS $b)
            {
                if( (trim($b->manufacturer_logo)!='') && (file_exists(JPATH_ROOT.'/components/com_jshopping/files/img_manufs/'.$b->manufacturer_logo)) )
                    $img = '/components/com_jshopping/files/img_manufs/'.$b->manufacturer_logo;
                else
                    $img = '/images/temp.png';

                echo
                '
                <div class="b-section__slide">
                    <div class="b-section__block">
                        <a href="/brand/'.$b->title.'"><img data-lazy="'.$img.'" alt="'.$b->title.'"></a>
                    </div>
                </div>
                ';
            }
            ?>
        </div>
    </div>
</section>
