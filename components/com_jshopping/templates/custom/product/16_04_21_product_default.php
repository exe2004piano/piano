<?php
	defined( '_JEXEC' ) or die();
	$db = JFactory::getDBO();

	if($z_user = get_current_user_z())
		$this->product->product_price = $this->product->price_reg;

	$cur_id = $this->product->product_id;

	$link = $db->setQuery("SELECT real_link FROM #__jshopping_products WHERE product_id={$cur_id}")->loadObject();

	$url = $_SERVER['REQUEST_URI'] . "#";
	$url = substr($url, 0, strpos($url, '#'))."&";
	$url = substr($url, 0, strpos($url, '&'))."=";
	$url = substr($url, 0, strpos($url, '='))."?";
	$url = substr($url, 0, strpos($url, '?'));

	if( ($link->real_link!='') && ($link->real_link != $url) )
    {
		$app = JFactory::getApplication();
		$app->redirect($link->real_link);
		$app->close();
    }

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


	/*
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
    */


	global $valute;
	$kurs_ye = $valute[2]['value'];
	$list_name = 'Listing';
	if(isset($_GET['utm_source']) && $_GET['utm_source']=='poisk_piano')
		$list_name = 'Search';

	$google_push = "
	gtag('event', 'view_item', {
        'content_type': 'product',
        'items': [
        {
              'id': '".$product->product_id."',
              'name': '".str_replace(Array('"', "'"), "`",$product->name)."',
              'list_name': '".$list_name."',
              'brand': 'Casio',
              'category': 'Цифровые пианино/Casio',
              'list_position': 1,
              'quantity': 1,
              'price': ".round($product->product_price/$kurs_ye, 2)."
        }
        ]
    });
    
    console.log('gtag_view_item');
    
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
			$dost_srok_rb = '≈1-2 дня';
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
			$dost_srok_minsk = $dost_srok_rb = ' уточняйте ';
			break;
		case '3' :
			$sklad_title = 'снят с производства';
			$sklad_status = 'none';
			$dost_srok_minsk = $dost_srok_rb = ' уточняйте ';
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
			$dost_srok_minsk = $dost_srok_rb = ' уточняйте ';
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
		case "warr24-12":
			$warr = "2 года гарантии + 1 год сервисного обслуживания";
			break;
	}
	// --- END лейбл с гарантией


	// --- определим есть ли запись ютуба:
	// --- переписать этот блок позже, когда уйдем от плагина ТАБС
	$video_img = false;
    $video = '';



	if(trim($product->tab_4)!='')
	{
	    /*
		$video = substr($product->description, strpos($product->description, '{youtube}')+strlen('{youtube}'));
		$video = trim(no_tags(substr($video, 0, strpos($video, '{'))));
	    */
	    $temp = explode("\n", $product->tab_4);
	    foreach ($temp AS $t)
        {
            $t = str_replace("=", "", trim($t));

            if($t!='')
            {
                $video = $t;
				$video_img = true;
            }
        }
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

    <?php
		$img = 'https://'.$_SERVER['SERVER_NAME'].'/'.JIMG.$this->images[0]->image_full;
    ?>
    <link itemprop="image" href="<?=$img;?>">

    <div itemprop="offers" itemtype="http://schema.org/Offer" itemscope="">
        <link itemprop="url" href="<?='https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];?>">
        <meta itemprop="availability" content="https://schema.org/InStock">
        <meta itemprop="priceCurrency" content="BYN">
        <meta itemprop="price" content="<?=(1.0*str_replace(" ", "", echo_price($product->product_price, 1, -1, $product)));?>">
    </div>

	<? include_once __DIR__.'/new_layout.php'; ?>


    <div class="layout-wrapper">



	<?php // --- Комплекты
		include(dirname(__FILE__)."/komplekts.php");
	?>

    <section class="b-nav js-menuWeypoint">
        <div class="b-nav__menu-wrap ">
            <div class="container">
                <nav class="b-nav__menu">


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
						<?php  if($product->reviews_count>0) { ?>
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
</div>

    <? include_position('new-banner-products'); ?>


    <section class="b-detal">
        <div class="container">
            <div class="row">

                <div class="col-md-8">
					<?php // TABBERS :
						include(dirname(__FILE__)."/tabbers.php");
					?>


                    <? include_position('product-dop'); ?>

					<?php // ZOO Item :
						include(dirname(__FILE__)."/product_to_zoo.php");
					?>

					<?php // отзывы :
						include(dirname(__FILE__)."/review.php");
					?>



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



	// ------- facebook remarketing:
	$q = "
        SELECT
        p.*, 
        `m`.`name_ru-RU` m_name,
        c1.`name_ru-RU` c1_name, 
        c2.`name_ru-RU` c2_name, 
        c3.`name_ru-RU` c3_name, 
        c4.`name_ru-RU` c4_name
        FROM #__jshopping_products AS p
        LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id = p.product_id
        LEFT JOIN #__jshopping_categories AS c4 ON c.category_id = c4.category_id
        LEFT JOIN #__jshopping_categories AS c3 ON c3.category_id = c4.category_parent_id
        LEFT JOIN #__jshopping_categories AS c2 ON c2.category_id = c3.category_parent_id
        LEFT JOIN #__jshopping_categories AS c1 ON c1.category_id = c2.category_parent_id
        LEFT JOIN #__jshopping_manufacturers AS m ON m.manufacturer_id = p.product_manufacturer_id
        WHERE p.product_id={$this->product->product_id}
    ";
	$db->setQuery($q);
	$a = $db->loadObject();

	$cat = $a->c4_name;
	if(trim($a->c3_name)!='')
		$cat = $a->c3_name . ' > ' . $cat;
	if(trim($a->c2_name)!='')
		$cat = $a->c2_name . ' > ' . $cat;
	if(trim($a->c1_name)!='')
		$cat = $a->c1_name . ' > ' . $cat;
	$title = str_replace(Array("<", ">", "'", '"', '`', '%', '&', '#', '@', '^'), " ", $a->{'name_ru-RU'});
	?>
<script>
    fbq('track', 'ViewContent', {
        content_type: 'product',
        content_ids: ['<?=$a->product_id;?>'],
        content_name: '<?=$title;?>',
        content_category: '<?=$cat;?>',
        value: <?=$a->product_price;?>,
        currency: 'BYN'
    });
</script>
<?
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



