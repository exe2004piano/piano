<?php 
	defined( '_JEXEC' ) or die();

if (count($this->related_prod)) { ?>

        <div class="b-detal__outherProduct">
            <h2 class="b-section__title b-section__title--notLink">C этим товаром покупают</h2>
            <ul class="b-detal__outherProduct-list">
                <?php
                    foreach($this->related_prod as $k=>$prod)
                    {
                ?>
                <li class="b-detal__outherProduct-item">
                    <div class="b-slider__lastImg">
                        <img src="<?php echo $prod->image; ?>" alt="">
                    </div>
                    <div class="b-slider__lastText">
                        <a href="<?php echo $prod->product_link;?>" class="b-slider__title"><?php echo $prod->name; ?></a>
                        <?php
                        /*
                        <div class="b-slider__productRate">
                            <span style="width:<?php echo $prod->average_rating*10;?>%"></span>
                            <!--<div class="b-slider__productRate-num">(10)</div>-->
                        </div>
                        */
                        ?>
                        <div class="b-slider__price"><?php echo echo_price($prod->product_price);?></div>
                        <button class="b-option__busket" onclick="event_send('Korzina_Dobavit', 'DobavitVkorzina'); add_to_basket(<?php echo $prod->product_id; ?>, 1); $(this).hide(); $(this).next().show(); return false;">ДОБАВИТЬ В КОРЗИНУ</button>
                        <div class="b-slider__price" style="display: none;">Товар добавлен!<br /><a href="/basket">Перейти к оформлению</a></div>
                    </div>
                </li>
                        <?php
                    }
                ?>
            </ul>
        </div>
<?php } ?>

