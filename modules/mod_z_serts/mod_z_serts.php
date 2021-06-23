<?php defined('_JEXEC') or die;
	$slider = (array)json_decode($params->get('slider'));
?>

<section class="certificates-wrap">
    <div class="certificates slick-carousel">

        <? foreach ($slider['img'] AS $i=>$item) { ?>
        <div class="certificates__slide">
            <a data-fancybox="gallery" href="/<?=$slider['img'][$i];?>" class="certificates__block">
                <img src="/<?=$slider['img'][$i];?>" alt="<?=$slider['title'][$i];?>" />
            </a>
        </div>
        <? } ?>

    </div>
</section>

