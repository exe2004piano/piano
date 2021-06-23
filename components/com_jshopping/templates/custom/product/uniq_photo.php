<?php defined( '_JEXEC' ) or die();

$db->setQuery("SELECT * FROM #__z_dop_pics WHERE product_id={$this->product->product_id} ORDER BY id DESC");
if($d_res = $db->loadObjectList())
{
?>
</section>
<section class="b-foto">
    <div class="container">
        <h2 class="b-section__title b-section__title--notLink">Фотографии <?php echo $product->name;?> у наших клиентов</h2>
        <div class="b-foto__show">Смотреть фото</div>
        <div class="b-foto__slider">
            <nav class="b-slider">
                <ul class="b-slider__list b-slider__list--foto" data-max="5">
                    <?php
                    foreach($d_res AS $img)
                    {
                    ?>
                    <li class="b-slider__item">
                        <div class="b-slider__content">
                            <div class="b-slider__reviewImg">
                                <a href="/images/catalog/<?php echo $img->pic; ?>" class="b-slider__reviewImg fancybox" rel="group2">
                                    <img src="/images/catalog/<?php echo $img->pic; ?>" alt="<?php echo $product->name;?>. Уникальные фотографии инструмента.">
                                </a>
                            </div>
                        </div>
                    </li>
                    <?php
                    }
                    ?>

                </ul>
                <div class="b-slider__nav b-slider__nav--left"></div>
                <div class="b-slider__nav b-slider__nav--right"></div>
            </nav>
        </div>
    </div>
</section>
<section>
<?php
}
?>