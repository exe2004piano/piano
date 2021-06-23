<?php
	defined( '_JEXEC' ) or die();
	$db = JFactory::getDBO();

	$cur_id = $this->product->product_id;

	global $jshop_product_id;
	global $jshop_product_price;

	$jshop_product_id = $cur_id;
	$jshop_product_price = $this->product->product_price;

	// --- запишем статистику какие товары посещал пользователь (BIG DATA)
	if( (isset($_COOKIE['lid'])) && ($_COOKIE['lid']*1!=$cur_id) ) // --- последний товар, в котором пользователь был и он отличен от текущего
	{
		$last_tov = $_COOKIE['lid']*1;
		$q = "SELECT popular FROM #__z_roistat_dop WHERE product_from={$last_tov} AND product_to={$cur_id}";
		$db->setQuery($q);
		$res = $db->loadObject();
		if($res->popular*1>0)
		{
			// --- запись есть, нужно апдейстнуть
			$db->setQuery("UPDATE #__z_roistat_dop SET popular=popular+1 WHERE product_from={$last_tov} AND product_to={$cur_id}");
			$db->execute();
		}
		else
		{
			// --- записи нет, создадим
			$db->setQuery("INSERT INTO #__z_roistat_dop (product_from, product_to, popular) VALUES ({$last_tov}, {$cur_id}, 1)");
			$db->execute();
		}
	}
	setcookie("lid", $cur_id);  // --- отметим, что последний посещенный товар текущий
	// --- // статистика какие товары посещал пользователь




	// --- для определения последних посещений
	$_COOKIE['prod_visit'] = isset($_COOKIE['prod_visit'])?$_COOKIE['prod_visit']:'';
	$prod_visit = array_unique(explode(',', $this->product->product_id.','.$_COOKIE['prod_visit']));
	$prod_visit = array_slice($prod_visit,0,10);

	$vis_result = "";
	foreach($prod_visit AS $p)
		if($p*1>0)
			$vis_result .= ($p*1) . ',';

	$product = $this->product;
	setcookie('prod_visit', $vis_result, null, '/');




	// отправим данные в гугл-коммерс:
	$cat_list = "";
	$db->setQuery("
SELECT c.`name_ru-RU` title, cp.`name_ru-RU` cp_title
FROM #__jshopping_categories AS c
LEFT JOIN #__jshopping_categories AS cp on c.category_parent_id=cp.category_id
WHERE c.category_id={$product->product_categories[0]->category_id}
");
	$cat_temp = $db->loadObject();
	$cat_list = $cat_temp->cp_title . " / " . $cat_temp->title;


	$db->setQuery("SELECT manufacturer_id id, `name_ru-RU` title FROM #__jshopping_manufacturers WHERE manufacturer_id={$product->product_manufacturer_id}");
	$manuf = $db->loadObject();

	$google_push = "";
	$google_push = "
dataLayer.push({
  'ecommerce': {
    'detail': {
        'actionField': {'list': 'Карточка ".str_replace(Array('"', "'"), "`",$product->name)."'},
      'products': [{
            'name': '".str_replace(Array('"', "'"), "`",$product->name)."',
       'id': '".$product->product_id."',
       'price': '".$product->product_price."',
       'brand': '".$manuf->title."',
       'category': '".str_replace(Array('"', "'"), "`",$cat_list)."',
       }]
     }
   }
});
";


	$doc = JFactory::getDocument();
	$doc->addScriptDeclaration($google_push);

	$dost_srok_minsk = $dost_srok_rb = '';
	switch($product->sklad)
	{
		case '0' :
			$sklad_title = 'в наличии';
			$sklad_status = 'inStock';
			$dost_srok_minsk = 'сегодня';
			$dost_srok_rb = '≈1-3 дня';
			break;
		case '1' :
			$sklad_title = 'на складе';
			$sklad_status = 'inStock';
			$dost_srok_minsk = '≈1-4 дня';
			$dost_srok_rb = '≈1-4 дня';
			break;
		case '2' :
			$sklad_title = 'нет в наличии';
			$sklad_status = 'none';
			break;
		case '3' :
			$sklad_title = 'снят с производства';
			$sklad_status = 'none';
			break;
		case '4' :
			$sklad_title = 'под заказ';
			$sklad_status = 'order';
			$dost_srok_minsk = '≈'.date("d.m.Y", time() + 14*24*60*60);
			$dost_srok_rb = '≈'.date("d.m.Y", time() + 16*24*60*60);
			break;
		case '5' :
			$sklad_title = 'анонсируемая модель';
			$sklad_status = 'notify';
			break;
	}


	// --- лейбл с гарантией если он прописан для товара либо для всей категории
	$nn = 'name_en-GB';
	$warr = '';
	$cat_id = $product->product_categories[0]->category_id;
	if(trim($product->$nn)!='')
	{
		$warr = trim($product->$nn);
	}
	else
	{
		$db->setQuery("SELECT `name_en-GB` warr FROM #__jshopping_categories WHERE category_id={$cat_id}");
		$res = $db->loadObject();
		$warr = trim($res->warr);
	}

	//if($warr!="")
	//    $warr = "<img src='/images/{$warr}.png' />";

	switch($warr)
	{
		case "warr1":
			$warr = "Гарантия 1 мес.";
			break;
		case "warr3":
			$warr = "Гарантия 3 мес.";
			break;
		case "warr6":
			$warr = "Гарантия 6 мес.";
			break;
		case "warr12":
			$warr = "Гарантия 1 год";
			break;
		case "warr24":
			$warr = "2 года гарантии!";
			break;
		case "warr36":
			$warr = "3 года гарантии!";
			break;
	}
	// --- END лейбл с гарантией


	// --- определим есть ли запись ютуба:
	// --- переписать этот блок позже, когда уйдем от плагина ТАБС
	$video_img = false;
	if(strpos($product->description, '{youtube}')>0)
	{
		$video = substr($product->description, strpos($product->description, '{youtube}')+strlen('{youtube}'));
		$video = trim(no_tags(substr($video, 0, strpos($video, '{'))));
		if($video!='')
			$video_img = true;
		/*
				'<li class="b-itemImg__imgItem b-itemImg__imgItem--video">
					<a href="https://www.youtube.com/embed/'.$video.'?autoplay=1" class="b-itemImg__imgLink fancybox fancybox.iframe" rel="group">
					<img src="/components/com_jshopping/files/img_products/thumb_'.$product->image.'" alt="">
				</a>
			</li>';
		*/
	}
	// --- END определим есть ли запись ютуба

	$img_3d = false;
	// --- если есть 3-д фотки
	if(file_exists(JPATH_ROOT.'/images/3d/'.$product->product_id.'/1.jpg'))
		$img_3d = true;
	// --- END если есть 3-д фотки

	// --- цены:
	$price_byn = echo_price($product->product_price, 1, -1, $product);
	$price_usd = echo_price($product->product_price, 2, -1, $product);
	$price_rur = echo_price($product->product_price, 3, -1, $product);
	$sk = "";
	if($product->product_old_price>0)
	{
		$price_byn_old = echo_price($product->product_old_price, 1, -1, $product);
		$price_usd_old = echo_price($product->product_old_price, 2, -1, $product);
		$price_rur_old = echo_price($product->product_old_price, 3, -1, $product);
		$sk = "<span>-".round(($product->product_old_price-$product->product_price)/$product->product_price*100)."%</span>";
	}
?>


<script type="text/javascript">
    (window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function() {
        try {
            rrApi.view( <?php echo $product -> product_id; ?> );
        } catch (e) {}
    })
</script>

<div itemscope itemtype="http://schema.org/Product">

	<? include_once __DIR__.'/new_layout.php'; ?>

	<? /*
  <div class="row" style="display: none;">

    <div class="col-md-7">

      <div class="b-itemImg">
        <div class="b-itemImg__option">
          <div class="b-slider__productRate">
            <span style="width:<?php echo $product->average_rating*10; ?>%"></span>
            <?php
                //if($product->reviews_count>0)
                echo '<a href="#" class="b-slider__productRate-num">(' . $product->reviews_count . ' отзывов)</a>';
                ?>
          </div>
          <div class="b-itemImg__name">Арт. <?php echo $product->product_ean; ?><span id="roicode"></span></div>
          <?php if ($product->label_id>0)
                echo
                    '<ul class="b-itemImg__market">
                        <li class="b-itemImg__marketLIst">
                            <span class="b-itemImg__marketLink b-itemImg__marketLink--type' . $product->label_id .'">' . $product->_label_name . '</span>
                    </li>
                </ul>';
            ?>
        </div>



        <div class="b-itemImg__imgWrap">
          <ul class="b-itemImg__imgList">
            <?php
                if($this->images)
                {
                    // --- выведем все картинки кроме главной
                    foreach($this->images AS $img)
                    {
                        $im = get_cache_photo(JIMG.$img->image_full, 85,85);
                        if($img->image_thumb!='thumb_'.$product->image)
                            echo
                                '
                                <li class="b-itemImg__imgItem" style="max-height: 80px!important;" >
                                    <a href="/components/com_jshopping/files/img_products/'.$img->image_full.'" class="b-itemImg__imgLink fancybox" rel="group">
                                <img src="'.$im.'" alt="">
                            </a>
                        </li>
                        ';
                    }
                }
                // выведем видеообзор если есть
                echo $video_img;
                // выведем 3д если есть
                echo $img_3d;
                ?>
          </ul>


          <?php
            if(sizeof($this->images)<5)
            {
            ?>
          <style>
          .b-itemImg__imgItem {
            max-height: 116px !important;
          }
          </style>
          <?php
            }
            ?>


          <div class="b-itemImg__imgMain">
            <a href="/components/com_jshopping/files/img_products/full_<?php echo $product->image;?>"
              class="b-itemImg__imgMain-link fancybox" rel="group">
              <img itemprop="image"
                src="/components/com_jshopping/files/img_products/full_<?php echo $product->image;?>" alt="">
            </a>
            <span class="b-itemImg__imgClick-show"><span>Кликни на картинку для увеличения</span></span>

            <div class="b-itemImg__imgWarranty">
              <?php echo $warr; ?>
            </div>
          </div>
        </div>

      </div>
    </div>



    <?php
$v_id = 1*$_COOKIE['currency'];
if(isset($_GET['prods_from_search_valute']))
    $v_id = $_GET['prods_from_search_valute']*1;

// --- если не задана валюта либо задана неверно, то установим по-умолчанию:
if( ($v_id<1) || ($v_id>3) )
    $v_id = 1;
$actve[$v_id] = ' active ';
?>


    <div class="col-md-5">
      <div class="b-option js-tabWrap">
        <div class="b-option__lineTitle">
          <ul class="b-filter__currency">
            <li class="b-filter__currencyIyem">
              <a href="#" class="b-filter__currencyLink <?php echo $actve[1];?>" data-tab="1"
                onclick="set_currency_(1); return true;">BYN</a>
            </li>
            <li class="b-filter__currencyIyem">
              <a href="#" class="b-filter__currencyLink <?php echo $actve[2];?>" data-tab="2"
                onclick="set_currency_(2); return true;">USD</a>
            </li>
            <li class="b-filter__currencyIyem">
              <a href="#" class="b-filter__currencyLink <?php echo $actve[3];?>" data-tab="3"
                onclick="set_currency_(3); return true;">RUR</a>
            </li>
          </ul>


          <ul class="b-option__list">
            <li class="b-option__item">
              <a href="#" class="b-option__link b-option__link--like"
                onclick="add_to_like('<?php echo $product->product_id;?>'); return false;"></a>
            </li>
            <li class="b-option__item">
              <a href="#" class="b-option__link b-option__link--compare"
                onclick="add_to_compare('<?php echo $product->product_id;?>'); return false;"></a>
              <span class="b-option__linkDetal">Добавить в сравнение</span>
            </li>
            <li class="b-option__item js_listWrap">
              <a href="#" class="b-option__link b-option__link--share js_listLink"></a>
              <ul class="b-option__linkList js_listBlock">
                <li class="b-option__linkItem">
                  <a href="#price_lower" class="b-option__linkLink"
                    onclick="$('#product_price_lower').val('<?php echo $product->name;?>');">Сообщить о снижении
                    цены</a>
                </li>
                <li class="b-option__linkItem">
                  <a href="#" class="b-option__linkLink" onclick="print(); return false;">Распечатать</a>
                </li>
                <li class="b-option__linkItem ">
                  <a href="#" class="b-option__linkLink ">Поделиться в соц-сетях</a>

                  <script type="text/javascript">
                  (function() {
                    if (window.pluso)
                      if (typeof window.pluso.start == "function") return;
                    if (window.ifpluso == undefined) {
                      window.ifpluso = 1;
                      var d = document,
                        s = d.createElement('script'),
                        g = 'getElementsByTagName';
                      s.type = 'text/javascript';
                      s.charset = 'UTF-8';
                      s.async = true;
                      s.src = ('https:' == window.location.protocol ? 'https' : 'http') +
                        '://share.pluso.ru/pluso-like.js';
                      var h = d[g]('body')[0];
                      h.appendChild(s);
                    }
                  })();
                  </script>

                  <div class="pluso" data-background="transparent"
                    data-options="small,square,line,horizontal,nocounter,theme=08"
                    data-services="vkontakte,odnoklassniki,facebook,twitter,google,moimir"></div>
                </li>
              </ul>
            </li>
          </ul>
        </div>



        <?php
// --- блок с ценой и условиями доставки
    include(dirname(__FILE__)."/price_dostavka.php");
?>

      </div>
    </div>

  </div>
*/ ?>

	<? if($product->sklad>0) { ?>
        <div data-retailrocket-markup-block="5d3b023697a5250724f8bd50" data-product-id="<?=$product->product_id;?>"></div>
	<? } ?>

	<?php // --- Комплекты
		include(dirname(__FILE__)."/komplekts.php");
	?>

    <section class="b-nav js-menuWeypoint">
        <div class="b-nav__menu-wrap ">
            <div class="container">
                <nav class="b-nav__menu">
                    <div class="b-nav__header">
                        <div class="b-nav__img">
                            <img src="/components/com_jshopping/files/img_products/thumb_<?php echo $product->image; ?>" alt="">
                        </div>
                        <div class="b-nav__title"><?php echo $product->name;?></div>
                        <div class="b-nav__price"><?php echo echo_price($product->product_price, $i, -1);?></div>
                        <div class="b-nav__links">
                            <a href="#" class="b-slider__optionBasket"
                               onclick="event_send('Korzina_Dobavit', 'DobavitVkorzina'); add_to_basket('<?php echo $cur_id;?>', 1); return false;">в
                                корзину</a>
                            <button data-remodal-target="oneClick" class="b-slider__oneClick" style="display: none;"
                                    onclick="event_send('Kupit_v_1klik1', 'Kupitv1klik1'); $('#product_one_click').val('<?php echo str_replace(Array("'", '"'), " ", $product->name); ?>'); $('#product_one_click_id').val('<?php echo $cur_id; ?>'); $('#product_one_click_price').val('<?php echo $this->product->product_price; ?>'); ">Купить
                                в 1 клик</button>
                            <a href="#" class="b-slider__optionLike"
                               onclick="add_to_like('<?php echo $cur_id;?>'); return false;"></a>
                            <a href="#" class="b-slider__optionCompare"
                               onclick="add_to_compare('<?php echo $cur_id;?>'); return false;"></a>
                        </div>
                    </div>
                    <ul class="b-nav__menu-list">
						<?php if(no_tags(trim($product->tab_1).trim($product->tab_3).trim($it->z_text_inv))!='') { ?>
                            <li class="b-nav__menu-item">
                                <a href="#tab_1" class="b-nav__menu-link js-anchor tab_1"> <span>Описание</span></a>
                            </li>
						<?php } ?>
						<?php if(trim($product->tab_4)!='') { ?>
                            <li class="b-nav__menu-item">
                                <a href="#tab_4" class="b-nav__menu-link js-anchor tab_4"> <span>Видео</span></a>
                            </li>
						<?php } ?>
						<?php if(trim($product->tab_2)!='') { ?>
                            <li class="b-nav__menu-item">
                                <a href="#characteristics_block" class="b-nav__menu-link js-anchor characteristics_block">
                                    <span>Характеристики</span></a>
                            </li>
						<?php } ?>
						<?php if($product->reviews_count>0) { ?>
                            <li class="b-nav__menu-item">
                                <a href="#go_to_review" class="b-nav__menu-link js-anchor go_to_review"> <span>Отзывы</span></a>
                            </li>
						<?php } ?>
                    </ul>
                </nav>
                <nav class="b-nav__option">
                    <ul class="b-header__optionList">
                        <li class="b-header__optionItem">
                            <a href="/#" class="b-header__optionLink b-header__optionLink--compare" data-num="0" id="compare_b"
                               onclick="if(this.href=='https://piano.by/#') return false;">
                                <span class="b-header__optionLink-num" id="compare_span_b">0</span>
                            </a>
                        </li>
                        <li class="b-header__optionItem">
                            <a href="/#" class="b-header__optionLink b-header__optionLink--like" data-num="0" id="like_b"
                               onclick="if(this.href=='https://piano.by/#') return false;">
                                <span class="b-header__optionLink-num" id="like_span_b">0</span>
                            </a>
                        </li>

                        <li class="b-header__optionItem b-header__optionItem--basket" onclick="location.href='/basket';">
                            <a href="#" class="b-header__optionLink b-header__optionLink--basket">
                                <div class="b-header__basket">
                                    <h5 class="b-header__basketTitle">Корзина</h5>
                                    <span class="b-header__basketText" id="basket_summ_prod"></span>
                                </div>
                            </a>
                            <div class="b-header__basketList">
                                <span class="b-header__basketEmpty">Ваша корзина пуста(</span>
                            </div>

                        </li>
                    </ul>
                </nav>
            </div>

        </div>
    </section>

    <section class="b-detal">
        <div class="container">
            <div class="row">

                <div class="col-md-8">
					<?php // TABBERS :
						include(dirname(__FILE__)."/tabbers.php");
					?>


					<?php // soundcloud
						/*
						if(strlen(trim($this->product->tab_5))>1)
						{
							if(strpos("  ".$this->product->tab_5, '<iframe')<1)
								$this->product->tab_5 = '<iframe width="100%" height="150" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'.$this->product->tab_5.'&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&visual=true"></iframe>';

							$this->product->tab_5 = str_replace('&amp;', '&', $this->product->tab_5);

							echo "\n{$this->product->tab_5}<br /><br /><br />\n";
						}
						*/
					?>




					<?php // ZOO Item :
						include(dirname(__FILE__)."/product_to_zoo.php");
					?>

					<?php // отзывы :
						include(dirname(__FILE__)."/review.php");
					?>


                    <div data-retailrocket-markup-block="5d3aded997a5281eb44a58aa"
                         data-product-id="<?=$this->product->product_id;?>"></div>


					<?php
						// --- render module "reviews_all":
						jimport( 'joomla.application.module.helper' );
						$module = JModuleHelper::getModules('reviews_all');
						$attribs['style'] = 'none';
						foreach($module as $moduleitem){
							echo JModuleHelper::renderModule($moduleitem, $attribs);
						}
					?>


                </div>






                <div class="col-md-4">
                    <div class="b-detal__rightColumn">

                        <!-- мануал -->
						<?php  include(dirname(__FILE__)."/manual.php"); ?>

                        <!-- сопутка -->
						<?php  include(dirname(__FILE__)."/related.php"); ?>

                    </div>
                </div>

            </div>
        </div>
    </section>


	<?php // уникальные фото у наших клиентов :
		include(dirname(__FILE__)."/uniq_photo.php");
	?>


	<?php // big_data - блок "вам так же может понравится" :
		// include(dirname(__FILE__)."/big_data.php");
	?>



</div><!-- itemscope -->


<?php

	$jshopConfig = JFactory::getConfig();
	$sitename = $jshopConfig->get('sitename');

	$tt_en = 'meta_title_en-GB';
	$meta_title_en = trim($this->product->$tt_en);
	if($meta_title_en!='')
		$meta_title_en = "({$meta_title_en})";
	$meta_title = $this->product->name . " " . $meta_title_en . " - " . $sitename;

	$tt_ru = 'meta_title_ru-RU';
	$meta_title_ru = trim($this->product->$tt_ru);
	if($meta_title_ru!='')
		$meta_title = $meta_title_ru;

	$document = JFactory::getDocument();
	$document->setTitle($meta_title);


?>



<?php
	if
	(
		($this->product->sklad==0) &&
		($this->product->product_price>0) &&
		(in_array($this->category_id*1, Array(7,8,11,15,16)))
	)
	{
		?>
        <!-- Rating@Mail.ru rem -->
        <script type="text/javascript">
            var _tmr = _tmr || [];
            _tmr.push({
                type: 'itemView',
                productid: '<?php echo $this->product->product_id; ?>',
                pagetype: 'product',
                list: '1',
                totalvalue: '<?php echo $this->product->product_price; ?>'
            });
        </script>
        <!-- Rating@Mail.ru rem -->
		<?php
	}



	$db->setQuery("SELECT * FROM #__z_config WHERE `name` = 'seo_template' ");
	$t = $db->loadObject();
	$temp = explode("\n", $t->value);

	$seo = Array();
	foreach($temp AS $t)
	{
		$t = trim($t);
		if($t=='')
			continue;
		$tt = explode('=', $t);
		$seo[trim($tt[0])] = trim(str_replace('{title}', $product->name , $tt[1]));
	}

	// meta_title_ru-RU, meta_description_ru-RU, meta_keyword_ru-RU
	$document = JFactory::getDocument();
	// $document->setMetadata('keywords', 'key1, key2, key3');
	if(trim($this->product->{'meta_title_ru-RU'})=='')
		$document->setTitle($seo['seo_title']);

	if(trim($this->product->{'meta_description_ru-RU'})=='')
		$document->setMetadata('description', $seo['seo_description']);

	if(trim($this->product->{'meta_keywords_ru-RU'})=='')
		$document->setMetadata('keywords', $seo['seo_keywords']);


    include_once __DIR__.'/admin.php';



