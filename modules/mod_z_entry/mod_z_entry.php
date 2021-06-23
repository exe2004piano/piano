<?php
defined('_JEXEC') or die;
$db = JFactory::getDbo();

$count = (int)$params->get('count');
$gbvote = (int)$params->get('gbvote');

if(!$all = $db->setQuery("SELECT * FROM #__easybook WHERE published=1 AND gbvote>={$gbvote} AND gbimg<>'' ORDER BY id DESC LIMIT {$count}")->loadObjectList())
    return;
?>



<div class="container">
    <h2 class="container-title">Отзывы</h2>

    <div class="accordion slick-carousel">


        <? foreach ($all AS $a) { ?>
        <div class="accordion-item">
            <div class="accordion-header">
                <div class="accordion-header-top">
                    <div class="accordion-header-avatar">
                        <img alt="Андрей Левадний" src="/<?=$a->gbimg;?>">
                    </div>

                    <div class="accordion-header-info">
                        <h6><?=$a->gbname;?></h6>
                        <p class="accordion-header-title"><?=$a->gbcomment;?></p>
                    </div>
                </div>

                <div class="accordion-body">
                    <p><?=$a->gbtext;?></p>
                </div>

                <button class="accordion-btn" tabindex="-1">
                    <span class="accordion-btn-expand">Читать отзыв</span>
                    <span class="accordion-btn-collapse">Свернуть</span>
                </button>
            </div>
        </div>
        <? } ?>


    </div>
</div>