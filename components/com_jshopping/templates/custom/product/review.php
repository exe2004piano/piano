<?
    defined( '_JEXEC' ) or die();
    global $db;
	global $factors;
	global $all_factors_num;
	global $marks;

	if($product->reviews_count==0)
		return;

	$revs = get_revs($product->product_id);
?>



<div class="blocks" >

    <div class="blocks__block">
        <p class="blocks__block-title" id="go_to_review">Отзывы о <?=$product->{"name_be-BY"};?></p>

        <div class="rating">
            <div class="rating__info">
                <? if($product->average_rating>0) { ?>
                    <div class="rating__info-item">
                        <span class="rating__label"><?=(float)$product->average_rating;?></span>
                    </div>
                <? } ?>
                <? if((int)$product->reviews_count>0) { ?>
                    <div class="rating__info-item">
                        <span><?=(int)$product->reviews_count;?> отзывов</span>
                    </div>
                <? } ?>
                <? if($all_factors_num>0) { ?>
                    <div class="rating__info-item">
                        <span><?=$all_factors_num;?> оценок</span>
                    </div>
                <? } ?>
            </div>



            <div class="rating__cols">
				<?	if(sizeof($factors)>0) { ?>
                <div class="rating__col">
                    <? foreach ($factors AS $name=>$f)
                       {
                            $f_avg = round($f['val']/$f['num'], 1);
                            if($f_avg<=1)   continue;
                            ?>
                            <div class="rating__row">
                                <div class="rating__row-text">
                                    <p><?=$name;?></p>
                                    <p><?=$f_avg;?></p>
                                </div>
                                <div class="rating__line">
                                    <div class="rating__line-inner" style="width: <?=$f_avg*20;?>%"></div>
                                </div>
                            </div>
                    <? } ?>
                </div>
                <? } ?>

				<? if($product->reviews_count>0) { ?>
                    <div class="rating__col rating__col-small">
                        <p class="rating__title">Отзывы с оценкой</p>

                        <?/*<form action="/" class="rating__form"> */?>

                        <div class="rating__form-row">

                            <div class="rating__checkbox">
                                <input type="radio" id="r0" data-mark="0" name="product_mark_select" checked>
                                <label for="r0" class="rating__checkbox-label"></label>
                            </div>
                            <p class="rating__form-text">Все оценки</p>
                        </div>
                                <? foreach ($marks AS $r=>$m) { if($m==0) continue; ?>
                                    <div class="rating__form-row">

                                        <div class="rating__checkbox">
                                            <input type="radio" data-mark="<?=$r;?>" id="r<?=$r;?>" name="product_mark_select">
                                            <label for="r<?=$r;?>" class="rating__checkbox-label"></label>
                                        </div>

                                        <div class="rating__stars">
                                            <div class="rating__stars-star" style="width: <?=$r*20;?>%"></div>
                                        </div>

                                        <p class="rating__form-text"><?=$m;?> отзыва</p>
                                    </div>
                                <? } ?>
                        <?/*</form>*/?>
                    </div>
				<? } ?>
            </div>
        </div>



        <? if($product->reviews_count>0) { ?>
            <div class="reviews-blocks">
                <? $i=0; ?>
                <? foreach ($revs AS $r) { $i++; ?>
					<? if($i>3) break;?>
                    <? include JPATH_ROOT.'/z/review_template.php'; ?>
                <? } ?>
                <? if(sizeof($revs)>3) { ?>
                    <a href="#" data-id="<?=$product->product_id;?>" data-p="<?=md5(json_encode($revs));?>" class="b-detal__characteristicsMore show_more_reviews">Показать все</a>
                <? } ?>
            </div>
        <? } ?>



    </div>

</div>
















<?php
    return;

    defined( '_JEXEC' ) or die();


$code = rand(1000, 99999);
$_SESSION['code'] = $code;

//if($this->product->reviews_count==0)
//    return;

// if(!$this->reviews)
//    return;


$q =
"
SELECT count(review_id) c, mark
FROM #__jshopping_products_reviews
WHERE product_id={$product->product_id} AND publish=1
GROUP BY mark
ORDER BY mark DESC
";
$db->setQuery($q);
$rev_res = $db->loadObjectList();


?>
    <div class="b-rating" id="review">
        <h2 class="b-section__title b-section__title--notLink">
            <span id="go_to_review" class="scroll_link scroll_tag">Отзывы о товаре</span>
            <div class="b-slider__productRate">
                <span style="width:<?php echo $product->average_rating*10;?>%"></span>
                <div class="b-slider__productRate-num">(<?php echo $this->product->reviews_count;?>)</div>
            </div>
        </h2>

        <ul class="b-rating__list">
            <?php
            foreach($rev_res AS $r)
            {
                ?>
                <li class="b-rating__item b-rating__item--star1">
					<div class="b-rating__title"><?php echo $r->mark; ?> звезд</div>
					<div class="b-rating__line">
    					<div class="b-rating__lineStar" style="width:<?php echo $r->c/$this->product->reviews_count*100; ?>%"></div>
					</div>
					<div class="b-rating__num">(<?php echo $r->c;?>)</div>
				</li>
                <?php
            }
            ?>
        </ul>
        <br />
        <a href="#review" class="b-coment__addComent" >ОСТАВИТЬ СВОЙ ОТЗЫВ</a>
        <div class="clr"></div>
        <br /><br />

    <div id="coment" class="b-coment"><ul>
        <?php $i=0; $class=""; ?>
        <?php foreach($this->reviews as $curr)
                {
                    $i++;
                    if($i>2)
                        $class="inv_rev";
        ?>
            <li class="b-coment__item <?php echo $class; ?>" >
                <h3 class="b-coment__title">
                    <span><?php print $curr->user_name?></span> - <span class="b-coment__date"><?php print formatdate($curr->time);?></span>
                    <div class="b-slider__productRate">
                        <span style="width:<?php echo $curr->mark*10; ?>%"></span>
                        <div class="b-slider__productRate-num">(<?php echo $curr->mark; ?>)</div>
                    </div>
                </h3>

                <?php
                /* <div class="b-coment__detal">от <span class="b-coment__name"><?php print $curr->user_name?></span> - <span class="b-coment__date"><?php print formatdate($curr->time);?></span></div> */
                ?>

                <div class="b-coment__text" >
                    <?php
                    if(1*trim($curr->yandex_rev_id)==0)
                        print nl2br($curr->review);
                    else
                    //    <script>
							
					// 			put_text_rew('" . base64_encode($curr->review) . "');

					// 		</script>
                    ?>
                </div>
        <?php
        /*
                <div class="b-coment__option">
                    <div class="b-coment__optionLeft">2 из 3 пользователей считают этот отзыв полезным</div>
                    <div class="b-coment__optionRight">
                        <span class="b-coment__usefulText">Был ли отзыв полезным?</span>
                        <div class="b-coment__usefulWrap">
                            <a href="#" class="b-coment__useful b-coment__useful--yes">Да</a> |
                            <a href="#" class="b-coment__useful b-coment__useful--no">Нет</a>
                        </div>
                    </div>
                </div>
        */
        ?>
            </li>
        <?php }
        ?>
        </ul>




        <div class="b-coment__line clearFix">
            <?php
            if($i>2)
            {
                ?>
                <a id="show_inv_rev" class="b-coment__addComent" href="#" onclick="show_more_rev(); return false;">Еще отзывы</a>
                <?php
            }
            ?>


            <?php if ($this->allow_review > 0)
            {
            ?>
                <div class="remodal-bg">
                    <section class="remodal b-modal" data-remodal-id="review" style="padding: 10px;">
                <?php JHTML::_('behavior.formvalidation'); ?>
                <span class="review"><?php print _JSHOP_ADD_REVIEW_PRODUCT?></span>
                <form action="<?php print SEFLink('index.php?option=com_jshopping&controller=product&task=reviewsave');?>" name="add_review" method="post" onsubmit="return validateReviewForm(this.name)">
                    <?php echo JHTML::_( 'form.token' );?>
                    <input type="hidden" name="product_id" value="<?php print $this->product->product_id?>" />
                    <input type="hidden" name="back_link" value="<?php print jsFilterUrl($_SERVER['REQUEST_URI'])?>" />
                    <table id="jshop_review_write" >
                        <tr>
                            <td>
                                <?php print _JSHOP_REVIEW_USER_NAME?>
                            </td>
                            <td>
                                <input type="text" name="user_name" id="review_user_name" class="inputbox" value="<?php print $this->user->username?>" required />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php print _JSHOP_REVIEW_USER_EMAIL?>
                            </td>
                            <td>
                                <input type="text" name="user_email" id="review_user_email" class="inputbox" value="<?php print $this->user->email?>" required />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php print _JSHOP_REVIEW_REVIEW?>
                            </td>
                            <td>
                                <textarea name="review" id="review_review" rows="4" cols="40" class="jshop inputbox" style="width:320px;" required></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php print _JSHOP_REVIEW_MARK_PRODUCT?>
                            </td>
                            <td>
                                <?php for($i=1; $i<=$this->stars_count*$this->parts_count; $i++){?>
                                    <input name="mark" type="radio" class="star {split:<?php print $this->parts_count?>}" value="<?php print $i?>" <?php if ($i==$this->stars_count*$this->parts_count){?>checked="checked"<?php }?>/>
                                <?php } ?>
                            </td>
                        </tr>

                        <tr>
                            <td style="text-align: left;">
                                <div style="float: left; margin: 4px 20px -4px 4px;">Код:</div>
                                <img src="/exe/captcha.php?text=<?php echo base64_encode($code); ?>" style="border: 1px solid #BBB;"/>
                            </td>
                            <td>
                                <input type="text" name="code" class="inputbox" required />
                            </td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>
                                <input type="submit" class="b-coment__addComent validate" value="ОСТАВИТЬ СВОЙ ОТЗЫВ" />
                            </td>
                        </tr>
                    </table>
                </form>
            <?php
            }
            else
            {
            ?>
                <div class="review_text_not_login"><?php print $this->text_review?></div>
            <?php
            }
            ?>
                        </section>
                    </div>


        </div>



    </div>


    </div>







<?php
    if ( ($this->display_pagination) && ($this->product->reviews_count>5) )
    {
        $url = $_SERVER['REQUEST_URI'];
        if(strpos($url, '?')>0)
            $url = substr($url, 0, strpos($url, '?'));

        $start = $_GET['start']*1;
        ?>
        <nav class="b-section__pagination">
                <ul class="pagination" id="pagination">
        <?php

        for($p=0;$p<=$this->product->reviews_count;$p+=5)
        {
            if($p==$start)
                $class = ' class="active disabled" ';
            else
                $class = ' ';

            echo "<li {$class}><a href=\"{$url}?start={$p}#go_to_review\" >".(($p+5)/5)."</a></li>";
        }

        ?>
        </ul>
            </nav>
        <?php
    }
?>















<?php

return;



$code = rand(1000, 99999);
$_SESSION['code'] = $code;
?>

    <a id="advtab5" href="#" onclick="return false;" >&nbsp;</a>

<?php if ($this->allow_review){?>
    <div class="review_header">
        <?php
        echo "<br /><strong>Отзывы о товаре `" . $this->product->meta_title . "`: </strong><br /><br />";
        ?>
    </div>
    <?php foreach($this->reviews as $curr){?>
        <div class="review_item">
            <div class="review_head"><span class="review_user"><?php print $curr->user_name?></span>, <span class='review_time'><?php print formatdate($curr->time);?></span></div>
            <div class="review_text">
                <?php
                if(1*trim($curr->yandex_rev_id)==0)
                    print nl2br($curr->review);
                else
                    echo "<script>put_text('" . base64_encode($curr->review) . "');</script>";
                ?>
            </div>
            <?php if ($curr->mark) {?>
                <div class="review_mark"><?php print showMarkStar($curr->mark);?></div>
            <?php } ?>
        </div>
    <?php }?>
    <?php if ($this->display_pagination){?>
        <table class="jshop_pagination">
            <tr>
                <td><div class="pagination">
                        <?php
                            //print $this->pagination;
                        ?>
                    </div>
                </td>
            </tr>
        </table>
    <?php }?>
    <?php if ($this->allow_review > 0){?>
        <?php JHTML::_('behavior.formvalidation'); ?>
        <span class="review"><?php print _JSHOP_ADD_REVIEW_PRODUCT?></span>
        <form action="<?php print SEFLink('index.php?option=com_jshopping&controller=product&task=reviewsave');?>" name="add_review" method="post" onsubmit="return validateReviewForm(this.name)">
            <?php echo JHTML::_( 'form.token' );?>
            <input type="hidden" name="product_id" value="<?php print $this->product->product_id?>" />
            <input type="hidden" name="back_link" value="<?php print jsFilterUrl($_SERVER['REQUEST_URI'])?>" />
            <table id="jshop_review_write" >
                <tr>
                    <td>
                        <?php print _JSHOP_REVIEW_USER_NAME?>
                    </td>
                    <td>
                        <input type="text" name="user_name" id="review_user_name" class="inputbox" value="<?php print $this->user->username?>"/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php print _JSHOP_REVIEW_USER_EMAIL?>
                    </td>
                    <td>
                        <input type="text" name="user_email" id="review_user_email" class="inputbox" value="<?php print $this->user->email?>" />
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php print _JSHOP_REVIEW_REVIEW?>
                    </td>
                    <td>
                        <textarea name="review" id="review_review" rows="4" cols="40" class="jshop inputbox" style="width:320px;"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php print _JSHOP_REVIEW_MARK_PRODUCT?>
                    </td>
                    <td>
                        <?php for($i=1; $i<=$this->stars_count*$this->parts_count; $i++){?>
                            <input name="mark" type="radio" class="star {split:<?php print $this->parts_count?>}" value="<?php print $i?>" <?php if ($i==$this->stars_count*$this->parts_count){?>checked="checked"<?php }?>/>
                        <?php } ?>
                    </td>
                </tr>

                <tr>
                    <td>Код: <span style="display:inline-block; background: white; border: 1px solid silver; color: silver; font-size:16px; font-weight:bolder; width:60px;text-align:center;"><?=$code?></span></td>
                    <td>
                        <input type="text" name="code" class="inputbox" />
                    </td>
                </tr>


                <?php print $this->_tmp_product_review_before_submit;?>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" class="button validate" value="<?php print _JSHOP_REVIEW_SUBMIT?>" />
                    </td>
                </tr>
            </table>
        </form>
    <?php }else{?>
        <div class="review_text_not_login"><?php print $this->text_review?></div>
    <?php } ?>
<?php }?>