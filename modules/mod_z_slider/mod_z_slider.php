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

$ids = explode(',', $params->get('ids'));

$id = "-1";

foreach ($ids AS $i)
{
    $i = 1*$i;
    if($i>0)
        $id .= ', '.$i;
}

$db->setQuery("SELECT * FROM #__z_slider WHERE public=1 AND id IN ({$id}) ORDER BY id DESC");
$res = $db->loadObjectList();

?>


<section class="b-slider__wrap">
    <div class="container">

        <div class="list-wrap">
            <? include_position("left-menu-home"); ?>
        </div>
        <div class="b-slider__banner">
            <div class="slider-banner slick-carousel">

                <? foreach ($res AS $a) { ?>
                    <? $a->link = str_replace('http://', 'https://', $a->link); ?>
                    <div class="slider-banner__slide">
                        <a href="<?=$a->link;?>" class="slider-banner__link">
                            <div class="slider-banner__img">
                                <div class="lazyload">
                                    <!--<img src="/<?=$a->pic; ?>" alt="" role="presentation">-->
                                </div>
                            </div>

                            <?php /*<div class="slider-banner__card <?=$a->info_color;?>">
                                <h2><?=$a->type;?></h2>
                                <p><?=$a->name;?></p>
                            </div> */?>
                        </a>
                    </div>
                <? } ?>
            </div>


            <div class="slider-banner__nav slick-carousel">
				<? foreach ($res AS $a) { ?>
                    <div class="slider-banner__slide">
                        <div class="slider-banner__img">
                                <img data-lazy="<?php echo get_cache_photo($a->pic, 150, 50); ?>" alt="" role="presentation">
                        </div>
                    </div>
                <? } ?>
             </div>

        </div>
    </div>
</section>


<? return; ?>










    <nav class="b-slider b-slider--main">
        <ul class="b-slider__listMain slider-for b-slider__list--main" data-max="1">

<?php
foreach($res AS $a)
{
    $type_color = $name_color = $info_color = $button_color = "";
    if(trim($a->type_color)!='')
        $type_color = ' style ="color: '.trim($a->type_color).'!important;"';
    if(trim($a->name_color)!='')
        $name_color = ' style ="color: '.trim($a->name_color).'!important;"';
    if(trim($a->info_color)!='')
        $info_color = ' style ="color: '.trim($a->info_color).'!important;"';
    if(trim($a->button_color)!='')
        $button_color = ' style ="color: '.trim($a->button_color).'!important;"';

	$a->link = str_replace('http://', 'https://', $a->link);
    $button = '';
    if($a->button=="1")
        $button = '<a href="'.$a->link.'" class="b-slider__mainLink" '.$button_color.'>УЗНАТЬ ПОДРОБНЕЕ</a>';
?>




    <li class="b-slider__item">
    <a href="<?php echo $a->link; ?>" >
        <div class="b-slider__content">
            <div class="b-slider__imgMain-wrap">
                <div class="b-slider__imgMain -desktop postloader_back" rel="https://piano.by<?php echo get_cache_photo($a->pic, 930, 400); ?>" style="background-image:url('https://piano.by/images/temp.png')" ></div>
                <div class="b-slider__imgMain -mobile postloader_back" rel="https://piano.by<?php echo get_cache_photo($a->pic_mobile, 720, 320); ?>" style="background-image:url('https://piano.by/images/temp.png')"  ></div>
            </div>
        </div>
    </a>
    </li>

    <?php
    /*
    Старый вариант :

    <li class="b-slider__item">
        <a href="<?php echo $a->link; ?>" >
                    <div class="b-slider__content">
                        <div class="b-slider__imgMain postloader_back" rel="https://piano.by/<?php echo $a->pic; ?>" style="background-image:url('https://piano.by/images/temp.png')" ></div>
                        <div class="b-slider__textBlock">
                            <?php

                            // <h3 class="b-slider__mainType" <?php echo $type_color; ?> ><?php echo $a->type; ?></h3>
                            // <h2 class="b-slider__mainName" <?php echo $name_color; ?> ><?php echo $a->name; ?></h2>
                            // <p class="b-slider__mainText" <?php echo $info_color; ?> ><?php echo $a->info; ?></p>
                            ?>
                            <?php echo $button; ?>
                        </div>
                    </div>
        </a>
    </li>
    */
}
?>

        </ul>
<!--
        <div class="b-slider__nav b-slider__nav--left b-slider__nav--mainL"></div>
        <div class="b-slider__nav b-slider__nav--right b-slider__nav--mainR"></div>
        <div class="b-slider__paginator"></div>
-->

        <ul class="slider-nav">
            <?php
            foreach($res AS $a)
            {
				echo "<!--".$a->pic."-->";
                $pre_img = get_cache_photo_150_slider($a->pic);

                ?>
                <li>
                    <img src="<?php echo $pre_img; ?>" alt="">
                </li>
                <?php
            }
            ?>
        </ul>


    </nav>
