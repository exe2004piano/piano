<?php
defined( '_JEXEC' ) or die();
$db = JFactory::getDBO();

global $cat_id;
global $sub_cats;
global $sub_cats_2;
global $all_extra;
global $all_extra_is_slide;
global $all_extra_names;
global $all_extra_description;
global $all_attribs;
global $num_products;
global $all_vendors;
global $all_colors;
global $cat_list;

$all_extra = null;
$all_extra_is_slide = null;
$all_extra_names = null;
$all_extra_description = null;
$sub_cats = null;
$sub_cats_2 = null;
$all_colors = null;
$cat_id = $this->category->category_id;

$cat_list = "";
$cat_list .= str_replace(Array('"', "'"), "`", $this->category->{'name_ru-RU'});

if($this->category->category_parent_id!=0)
{
    $db->setQuery("SELECT `name_ru-RU` title, category_parent_id FROM #__jshopping_categories WHERE category_id={$this->category->category_parent_id}");
    $temp = $db->loadObject();
    $cat_list = str_replace(Array('"', "'"), "`",$temp->title) . "/" . $cat_list;
    if($temp->category_parent_id>0)
    {
        $db->setQuery("SELECT `name_ru-RU` title, category_parent_id FROM #__jshopping_categories WHERE category_id={$temp->category_parent_id}");
        $temp = $db->loadObject();
        $cat_list = str_replace(Array('"', "'"), "`",$temp->title) . "/" . $cat_list;
    }
}

// --- найдем подкатегории для данной:
$db->setQuery("SELECT  `name_ru-RU` title, category_id, category_image FROM #__jshopping_categories WHERE category_parent_id={$cat_id} AND category_publish=1 ORDER BY ordering");
// $cat_child - будем списком ID текущей категории и её дочерних
if($sub_cats = $db->loadObjectList())
{
    foreach($sub_cats AS $c)
        $cat_child .= $c->category_id . " , ";
}

// найдем дочерние у дочерних
// это конечно говнометод, но пока иначе никак
// нужно это для определения промежуточный у нас список или конечный
// всё из-за разной вложенности разделов
$db->setQuery("SELECT `name_ru-RU` title, category_id, category_image FROM #__jshopping_categories WHERE category_parent_id IN ({$cat_child} -1) AND category_publish=1 ORDER BY ordering");
$sub_cats_2 = $db->loadObjectList();
// --- теперь добавим текущую категорию к дочерним
$cat_child .= $cat_id;


if(sizeof($sub_cats_2)==0)
{
    // --- выберем все возможные цвета в категории:
    $q = "
      SELECT p.extra_field_8
      FROM #__jshopping_products AS p
      LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
      WHERE c.category_id IN ({$cat_child})
      GROUP BY p.extra_field_8
      ";
    $db->setQuery($q);
    if($colors = $db->loadObjectList())
    {
        foreach($colors AS $c)
            $all_colors[$c->extra_field_8] = 1;
    }
}








// --- выберем все характеристики
// --- это накладно для памяти, но иначе мы будем делать 100500 обращений к базе
// --- в общем так быстрее
// --- даже если значений характеристик будет 5000 - это 500кб памяти максимум
// --- зато прирост в скорости норм
$db->setQuery("SELECT id, field_id, `name_ru-RU` title FROM #__jshopping_products_extra_field_values ORDER BY field_id, ordering");
$res = $db->loadObjectList();
foreach($res AS $e)
{
	$all_extra[$e->field_id][$e->id]=$e->title;
}


$db->setQuery("SELECT id, `name_ru-RU` title, is_slide, min_slide, max_slide, `description_ru-RU` FROM #__jshopping_products_extra_fields ORDER BY id");
$res = $db->loadObjectList();
foreach($res AS $e)
{
	$all_extra_description[$e->id] = $e->{'description_ru-RU'};
    $all_extra_names[$e->id]=$e->title;
    $all_extra_is_slide[$e->id]['is_slide']=1*trim($e->is_slide);
    $all_extra_is_slide[$e->id]['min_slide']=1*trim($e->min_slide);
    $all_extra_is_slide[$e->id]['max_slide']=1*trim($e->max_slide);
}
unset($res);



$db->setQuery("SELECT manufacturer_id id, `name_ru-RU` title FROM #__jshopping_manufacturers ORDER BY id");
$res = $db->loadObjectList();
foreach($res AS $e)
    $all_vendors[$e->id]=$e->title;
unset($res);



$db->setQuery("SELECT * FROM #__jshopping_free_attr ORDER BY id");
$res = $db->loadObjectList();
foreach($res AS $e)
    $all_attribs[$e->id] = $e;
unset($res);




?>

<?/*
<script type="text/javascript">
(window["rrApiOnReady"] = window["rrApiOnReady"] || []).push(function() {
  try {
    rrApi.categoryView( < ? php echo $this - > category - > category_id; ? > );
  } catch (e) {}
})
</script>
*/ ?>

<div class="section__title">
  <h1><?php print $this->category->name ;?></h1>
  <input type="hidden" id="cat_id" value="<?php echo $cat_id; ?>" />
</div>

<div class="section__row">
  <div class="section__row-item section__row-btn">
    <a href="#" class="bv-btn bv-btn--third" id="filter-btn">
      <div class="bv-btn__icon">
        <svg class="filter-icon">
          <use class="filter-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#filter"></use>
        </svg>
      </div>
      <span class="bv-btn__text">Фильтр</span>
    </a>
  </div>
  <div class="section__row-item section__row-filter" id="filter-wrap">
    <div class="b-filter-wrap">
      <a href="#" class="bv-btn b-filter-btn" id="hide-filter">Скрыть фильтр</a>
    </div>

    <?php
            $modules = JModuleHelper::getModules('left-filter');
            if ($modules && is_array($modules)) {
                foreach ($modules as $module) {
                    echo JModuleHelper::renderModule($module);
                }
            }
            ?>

  </div>


  <div class="section__row-item">
    <!-- module slider -->
    <?php
        $modules = JModuleHelper::getModules('shopping-top');
        if ($modules && is_array($modules)) {
            foreach ($modules as $module) {
                echo JModuleHelper::renderModule($module);
            }
        }
        ?>

    <a href="#" class="popular-links-show">Смотреть еще</a>
    <!-- // module slider -->

    <div data-retailrocket-markup-block="5d3981c697a5251f7847e9fd"
      data-category-id="<?php echo $this->category->category_id; ?>"></div>



    <!-- component category -->
    <?php
        // --- найдем подкатегории для данной:
        $db->setQuery("SELECT  `name_ru-RU` title, category_id, category_image FROM #__jshopping_categories WHERE category_parent_id={$cat_id} AND category_publish=1 ORDER BY ordering");
        if($sub_cats_2)
        {
            ?>
    <nav class="b-mainCatalog">
      <ul class="b-mainCatalog__list">
        <?php
                    foreach($sub_cats AS $c)
                    {
                        $link = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$c->category_id);

                        if( (trim($c->category_image)=='') || (!file_exists(JPATH_ROOT. "/components/com_jshopping/files/img_categories/{$c->category_image}")) )
                            $c->category_image = 'temp.jpg';


                        ?>
        <li class="b-mainCatalog__item">
          <a href="<?php echo $link; ?>" class="b-mainCatalog__link">
            <div class="b-mainCatalog__img">
              <img src="/components/com_jshopping/files/img_categories/<?php echo $c->category_image;?>" alt="">
            </div>
            <h4 class="b-mainCatalog__title"><?php echo $c->title;?><span>
                <!-- num --></span></h4>
          </a>
        </li>
        <?php
                    }
                    ?>
      </ul>
    </nav>
    <?php
        }
        // --- // нашли подкатегории для данной
        else
        {
            // --- нет подкатегорий 2 уровня - значит у нас конечный листинг
            $start = $_GET['start'];
        ?>


    <div class="b-filter__line">

      <div class="b-filter__numberOf" id="all_filter_product_number">Найдено товаров: <span
          id="all_num_products_show"></span>
      </div>

      <?php
                global $valute;
                $v_id = 1*$_COOKIE['currency'];
                if(isset($_GET['prods_from_search_valute']))
                    $v_id = $_GET['prods_from_search_valute']*1;

                // --- если незадана валюта либо задана неверно, то установим по-умолчанию:
                if( ($v_id<1) || ($v_id>3) )
                    $v_id = 1;

                $valute_active = null;
                $valute_active[$v_id] = " active ";
                ?>


      <ul class="b-filter__currency b-filter__currency--productFilter">
        <li class="b-filter__currencyIyem ">
          <a href="#" class="b-filter__currencyLink <?php echo $valute_active[1];?>"
            onclick="set_currency(1); return false;">BYN</a>
        </li>
        <li class="b-filter__currencyIyem ">
          <a href="#" class="b-filter__currencyLink <?php echo $valute_active[2];?>"
            onclick="set_currency(2); return false;">USD</a>
        </li>
        <li class="b-filter__currencyIyem ">
          <a href="#" class="b-filter__currencyLink <?php echo $valute_active[3];?>"
            onclick="set_currency(3); return false;">RUR</a>
        </li>
      </ul>


      <?php
                    $credit_price = 1*$_COOKIE['credit_price'];
                    if($credit_price==0)
                        echo '<a href="#" class="b-filter__credit" onclick="set_credit_price(1); return false;">В кредит</a>';
                    else
                        echo '<a href="#" class="b-filter__credit active" onclick="set_credit_price(0);  return false;">Полная цена</a>';
                ?>



      <?php
                    $order = null;
		            if((int)$_COOKIE['filter_order']==0)
						$_COOKIE['filter_order'] = 1;

                    $order[(int)$_COOKIE['filter_order']] = ' SELECTED ';

                    $screen_type = null;
                    $screen_type[(int)$_COOKIE['screen_type']] = " active ";

                ?>
      <div class="b-filter__select">
        <select name="filterSelect" id="filter_order">
          <?/*<option <?php echo $order[0]; ?> value="0">По-умолчанию</option>*/?>
          <option <?php echo $order[1]; ?> value="1">Сначала дешевые</option>
          <option <?php echo $order[11]; ?> value="11">Сначала дорогие</option>
          <option <?php echo $order[2]; ?> value="2">По рейтингу</option>
          <option <?php echo $order[3]; ?> value="3">По популярности</option>
        </select>
      </div>


      <ul class="b-filter__screenList">
        <li class="b-filter__screenItem">
          <a href="#" class="b-filter__screenLink b-filter__screenLink--type1 <?php echo $screen_type[1];?>" rel="1"
            data-screen="screenList"></a>
        </li>
        <li class="b-filter__screenItem">
          <a href="#" class="b-filter__screenLink b-filter__screenLink--type2 <?php echo $screen_type[0];?>" rel="0"
            data-screen="screenItems"></a>
        </li>
        <li class="b-filter__screenItem">
          <a href="#" class="b-filter__screenLink b-filter__screenLink--type3 <?php echo $screen_type[2];?>" rel="2"
            data-screen="screenTable">
            <span class="bars">
              <span class="bar"></span>
              <span class="bar"></span>
              <span class="bar"></span>
            </span>
          </a>
        </li>
      </ul>

    </div>




    <?php
                // --- выведем выбранные параметры по фильтру и товары

            include_once(JPATH_ROOT."/components/com_jshopping/get_fields_panel.php");
            ?>



    <div class="items-wrap">

      <div class="list"
        data-screen="<?php if(isset($screen_type[0])) echo "screenItems"; elseif(isset($screen_type[1])) echo "screenList"; else echo "screenTable";?>"
        id="products_ul">
        <?
            // вывод листинга товаров
            global $google_push;
            $google_push = "";
            include(JPATH_ROOT."/components/com_jshopping/get_products_by_filter.php");
            if($google_push!="")
            {
                $google_push = substr($google_push, 0, strrpos($google_push, ','));
                $google_push = "
                
                gtag('event', 'view_item_list', {
                    'items': [ " .
                        $google_push .
                    " 
                    ] 
                }); ";

                $doc = JFactory::getDocument();
                $doc->addScriptDeclaration($google_push);
            }
        ?>
      </div>

      <?php
                // $num_products --- количество продуктов общее
                // 20 - на странице
                // $start - начало отображения
            ?>


      <nav class="b-section__pagination">
        <ul class="pagination" id="pagination">
          <?php
                        $num_pages = ($num_products / 20);
                        $cur_page = $start/20;
                        $url = str_replace("start={$start}", "start=<!--start-->", $_SERVER['REQUEST_URI']);
                        $url = str_replace('https://', '', $url);
                        $url = str_replace('http://', '', $url);
                        $url = substr($url, strpos($url, '/'));

                        if(strpos(" ".$url, '<!--start-->')==0)
                        {
                            if(strpos($url, '?')<1)
                                $url .= "?start=<!--start-->";
                            else
                                $url .= "&start=<!--start-->";
                        }



                        for($p=0;$p<=$num_pages;$p++)
                        {
                            if($p==$cur_page)
                                $active = ' class="active disabled" ';
                            else
                                $active = '';


                            $cur_url = str_replace("<!--start-->", ($p*20), $url);


                            echo '<li'.$active.'><a href="'.$cur_url.'" >'.($p+1).'</a></li>';
                            //echo '<li'.$active.'><a href="#" onclick="get_page_start('.($p*20).'); return false;">'.($p+1).'</a></li>';
                        }
                    ?>
        </ul>
      </nav>

      <?php
        }

        ?>

      <?php
        // --- популярные товары из категории:
        // найдем по 16 популярных товаров, новинок и прочего что есть в лейблах из текущей категории либо из её дочерних
        // текущая категория + её дочерние 1 уровня у нас есть, найдем категории 2 уровня вложенности и добавим к имеющимся
            $q =
            "
            SELECT p.`name_ru-RU` title, p.product_id, p.sklad, p.product_price, p.product_old_price, p.product_ean, p.image, p.average_rating, c.category_id, p.label_id, lab.name label_name
            FROM #__jshopping_products AS p
            LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
            LEFT JOIN #__z_jshop_category_products AS popular ON popular.product_id=p.product_id
            LEFT JOIN #__jshopping_product_labels AS lab ON lab.id=p.label_id
            WHERE " .
//                c.category_id IN ( SELECT category_id FROM #__jshopping_categories WHERE category_parent_id IN ({$cat_child}) ) AND
            "
            p.product_publish=1 AND popular.category_id={$cat_id}
            ORDER BY popular.id DESC
            LIMIT 0,16
            ";
            $db->setQuery($q);
            $products = $db->loadObjectList();
            if($products)
            {
                ?>
      <section class="b-section__like">
        <h2 class="b-section__title b-section__title--notLink">Популярные товары</h2>


                  <div class="list slick-carousel">

            <?php echo include(JPATH_ROOT.'/components/com_jshopping/get_label_products.php'); ?>

            <?php
                // --- если популярных товаров в разделе меньше 4, то нужно добавить каких-нибудь еще товаров из раздела, которые есть на складе либо под заказ, и отсортируем их по хитам:
                if(sizeof($products)<4)
                {
                    $q =
                        "
                        SELECT p.`name_ru-RU` title, p.product_id, p.sklad, p.product_price, p.product_old_price, p.product_ean, p.image, p.average_rating, c.category_id, p.label_id, lab.name label_name
                        FROM #__jshopping_products AS p
                        LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
                        LEFT JOIN #__jshopping_product_labels AS lab ON lab.id=p.label_id
                        WHERE
                        c.category_id IN ( SELECT category_id FROM #__jshopping_categories WHERE category_parent_id IN ({$cat_child}) ) AND
                        p.product_publish=1 AND (p.sklad=0 OR p.sklad=1 OR p.sklad=4)
                        ORDER BY p.hits DESC
                        LIMIT 0,4
                        ";
                    $db->setQuery($q);
                    $products = $db->loadObjectList();
                    echo include(JPATH_ROOT.'/components/com_jshopping/get_label_products.php');
                }
            ?>

                </div>

      </section>
      <?php
            }
        // --- // популярные товары из категории
        ?>





      <?php
            // по тому же принципу наверное тут выводим товары с меткой "новинка"
        ?>



      <?php
        $q = "
        SELECT i.application_id, i.name, i.alias, i.elements, i.params, cat.alias cat_alias
        FROM #__zoo_item AS i
        LEFT JOIN #__zoo_application AS cat ON cat.id=i.application_id
        LEFT JOIN #__z_zoo_to_categories AS z_c ON z_c.zoo_id=i.id
        WHERE z_c.cat_id={$cat_id}
        ORDER BY z_c.id
        DESC LIMIT 8
        ";
        $db->setQuery($q);
        if($res = $db->loadObjectList())
        {
            echo '<section class="b-review b-review--second" >
							<div class="b-section__title" style="display: none">
								<span>Текстовые обзоры</span>
							</div>
							<div class="b-review__items b-review__slider slick-carousel" style="display: none">';

            // $res->elements = json_decode($res->elements);
            // print_r($res);


            foreach($res AS $a)
            {
                // --- дебильным способом получим текст новости:
                $text_new = '';
                $e = json_decode($a->elements);
                foreach($e AS $key=>$value)
                {
                    foreach($value AS $k=>$v)
                    {
                        if(strlen($v->value)>100)
                            $text_new = $v->value;
                    }
                }

                if($text_new=='')
                    continue;
                $img="";
                if(strpos($text_new, '<img')>0)
                {
                    $img = substr($text_new, strpos($text_new, '<img'));
                    $img = substr($img, 0, strpos($img, '>')+strlen('>'));
                }
                else
                {
                    $img = "<img src='/images/temp_news.jpg' />";
                }

                $title = $a->name;
                $text_new = trim(no_tags($text_new));
                $text_new = substr($text_new, 0, 200);
                $text_new = substr($text_new, 0, strrpos($text_new, ' ')) . '...';
                $link = "/".$a->cat_alias."/item/".$a->alias;

                echo
                '<div class="b-review__item">
                    <a href="'.$link.'"class="b-review__block">
                        <div class="lazyload"><!--' . $img . '--></div>
                        <p>'. $text_new .'</p>
                    </a>
                </div>';

            }

            echo '</div>
			</section>';

        }
        ?>

      <?php
        // Часто ищут -- чё? куда?
        ?>


      <?php
        if($start==0)
        {
        ?>


      <!-- module in categories -->
      <?php
            $modules = JModuleHelper::getModules('in-categories');
            if ($modules && is_array($modules)) {
                foreach ($modules as $module) {
                    echo JModuleHelper::renderModule($module);
                }
            }
            ?>
      <!-- // module in categories -->



      <div class="b-text__contentWrap">
        <div class="b-text__content">
          <?php
                $desc_name = 'description_ru-RU';
                echo $this->category->$desc_name;
                ?>
        </div>
      </div>
      <?
        }
        ?>
      <?php
        // видео обзоры - чё? куда?
        ?>




      <!-- // component category -->

    </div>
  </div>


  <?php
if(sizeof($_GET)>0)
{
    $url = $_SERVER['REQUEST_URI'].'?';
    $url = substr($url, 0, strpos($url, '?')).'#';
    $url = substr($url, 0, strpos($url, '#')).'&';
    $url = substr($url, 0, strpos($url, '&'));

    $doc = JFactory::getDocument();
    $doc->addHeadLink($url, 'canonical', 'rel', '');
}


?>



  <?php
/*

<div class="jshop">
<?php if (!count($this->categories) && JRequest::getVar('controller')!='search'){
	$modules = JModuleHelper::getModules('breadcrumbs');
if ($modules && is_array($modules)) {
	foreach ($modules as $module) {
		echo JModuleHelper::renderModule($module);
	};
} } ?>
  <?php if (!count($this->categories) && JRequest::getVar('controller')!='search'){
	$modules = JModuleHelper::getModules('banner');
if ($modules && is_array($modules)) {
	foreach ($modules as $module) {
		echo JModuleHelper::renderModule($module);
	};
} } ?>
  <?php if (JRequest::getVar('controller')!='search'){ ?>
  <h1 class="list_category"><?php print $this->category->name?></h1>
  <?php } ?>


  <?php

$temp =
    "
                <li>
                    <div class=\"block_item\">
                        <div class=\"item_name\">
                            <span class=\"h3\"><a href=\"/items/<!--link-->\"><!--title--></a></span>
                        </div>
                        <div class=\"item_image\">
                            <span>
                                <a href=\"/items/<!--link-->\">
                                    <div class=\"img\">
                                        <img src=\"/images/attrib/<!--link-->.jpg\" alt=\"<!--title-->\" />
                                    </div>
                                </a>
                            </span>
                        </div>
                    </div>
                </li>
    ";


$db = JFactory::getDBO();
$q = "SELECT `name_en-GB` link, `name_ru-RU` title FROM #__jshopping_free_attr WHERE catid_1={$cat_id} OR catid_2={$cat_id} OR catid_3={$cat_id} ORDER BY id DESC";
$db->setQuery($q);
$attribs = $db->loadObjectList();

$text = "";
foreach($attribs AS $a)
{
    $text .= str_replace(
        Array("<!--link-->", "<!--title-->"),
        Array($a->link, $a->title),
        $temp
    );
}

$count = sizeof($attribs);
if($count>3)
    $count = 3;

$attr_razdel = "";

if($count>0)
{
    $attr_razdel =
"
  <link rel=\"stylesheet\" href=\"https://piano.by/modules/mod_jt_jshopping_label_products/css/style.css\" type=\"text/css\" />
  <script src=\"https://piano.by/modules/mod_jt_jshopping_label_products/js/jquery.bxSlider.min.js\" type=\"text/javascript\" ></script>
  <script type = \"text/javascript\">if (jQuery) jQuery.noConflict();</script>


<script type=\"text/javascript\">
    jQuery(document).ready(function(){
        jQuery('#label_slider99').bxSlider({
            prevSelector:'.jt_button_prev_l_99',
            nextSelector:'.jt_button_next_l_99',
            mode: 'horizontal',
            speed: 500,
            controls: true,
            auto: false,
            pause: 3000,
            autoDelay: 0,
            autoHover: false,
            pager: false,
            pagerType: 'full',
            pagerLocation: 'bottom',
            pagerShortSeparator: '/',
            displaySlideQty: {$count},
            moveSlideQty: {$count}	});
    });
</script>


<style type=\"text/css\">
    #label_slider99 li { background: none;  width:180px; height:240px;}
    .jt_button_prev_l_99 a, .jt_button_next_l_99 a {height:220px;}
    #label_slider99 img
    {
    width: 150px; height: 150px;
    }
    #label_slider99 li .block_item
    {
    border: 1px solid #EEE;
    margin: 5px;
    padding: 10px;
    text-align: center;
    }

    #label_slider99 li .block_item .item_image
    {
        margin-top: 10px;
    }

    #label_slider99 .item_name
    {
        height: 48px;
    }

</style>


<br /><br /><b><center>Рекомендуем:</center></b>

<div class=\"mod_jt_jshopping_label_products \">
    <div id=\"jt_jshopping_label_slider\">
        <div class=\"jt_button_prev_l_99 jt_prev_l\"></div>
        <ul id=\"label_slider99\">

        {$text}


        </ul>
        <div class=\"jt_button_next_l_99 jt_next_l\"></div>
    </div>
</div>
    <div class=\"clr\"></div>
";
}

?>

  <?php if (count($this->categories)){ ?>
  <div class="jshop_list_category">

    <?php $i = 0; ?>
    <?php foreach($this->categories as $k=>$category){?>
    <?php if($i==600) { ?>
    <div class="hidden-cat" style="display:none;">
      <?php } ?>
      <?php if ($i % 3 == 0) { ?>
      <div class="jshop_category_row">
        <?php } ?>


        <?php $ordering = $jshopConfig->category_sorting == 1 ? "ordering" : "name"; ?>
        <?php $cat = &JTable::getInstance('category','jshop'); ?>
        <?php $cat->load($category->category_id); ?>
        <?php $sub_categories = $cat->getChildCategories($ordering, 'asc', $publish = 1); ?>
        <div class="jshop category">
          <?php if($category->category_image){ ?>
          <div class="category_image">
            <a class="category_link" href="<?php echo $category->category_link; ?>"><img class="jshop_img"
                src="<?php print $this->image_category_path;?>/<?php print $category->category_image ?>"
                title="<?php print htmlspecialchars($category->name)?>" /></a>
          </div>
          <?php } ?>
          <h2 class="category_title">
            <a class="category_link" href="<?php echo $category->category_link; ?>"><?php print $category->name?>
              <?php
if (!isset($this->product_categories)){
            $query = "SELECT * FROM `#__jshopping_products_to_categories` WHERE category_id='".$db->escape($category->category_id)."'";
            $db->setQuery($query);
            $category->count_products = $db->loadObjectList();
        }

// echo '<span class="count">('.count($category->count_products).')</span>';
// -- ___EXE
// -- � ��������� ����������� ����� � ������������ ������ � ��������� ������ �� 1 ������� ����
// -- �� ������ ��� ������������� ����������� ��������� ��������
// -- �� �� �����, ������ ��� �� 1 ������� ����, ���� � ��� 0 ������� � ���������:
if(count($category->count_products)>0)
	echo '<span class="count">('.count($category->count_products).')</span>';
else
{
	$category_id = $category->category_id;
	$db = JFactory::getDBO();
	$db->setQuery("SELECT category_id FROM #__jshopping_categories WHERE category_parent_id={$category_id}");
	$cats = $db->loadObjectList();

 	$count = 0;
 	$list_id = " 1=0 ";
 	foreach($cats AS $c)
 		$list_id .= " OR category_id={$c->category_id} ";

	$db->setQuery("SELECT count(product_id) c FROM #__jshopping_products_to_categories WHERE {$list_id} " );
	$c = $db->loadObject();
	echo '<span class="count">('.$c->c.')</span>';
}
// -- /___EXE

?>

            </a>
          </h2>
        </div>
        <?php if (($i % 3 == 2) || ($i == count($this->categories) - 1)) { ?>
      </div>
      <?php } ?>

      <?php if (($i > 500) && ($i == count($this->categories) - 1)) { ?>
    </div>
    <a href="#" id="open" class='closed' onClick='return toggleShow("<?php echo '.hidden-cat' ?>" ,this)'></a>
    <?php } ?>

    <?php $i++; ?>
    <?php } ?>
    <?php if ($category->short_description) { ?><p class="category_short_description">
      <?php print $category->short_description?></p><?php } ?>
  </div>
  <?php } ?>
  <?php $modules = JModuleHelper::getModules('custom-products');
if ($modules && is_array($modules)) {
	foreach ($modules as $module) {
		echo JModuleHelper::renderModule($module);
	};
}
?>
  <?php
if  (!JFactory::getApplication()->input->getInt('start') && !JFactory::getApplication()->input->getInt('limitstart'))
{

    if(strpos($this->category->description, '<hr id="system-readmore" />')>0)
    {
        $this->category->description = str_replace(
            '<hr id="system-readmore" />',
            "
            <span class=\"my_readmore\" onclick=\"jQ('.my_readmore').hide(); return toggleShow('#exe_readmore',this);\">Читать далее...</span>
            <br  class=\"my_readmore\"/><br  class=\"my_readmore\"/>
            <div id=\"exe_readmore\">
            ",
            $this->category->description
        ) . "</div>";


    }


    print $this->category->description;

}?>


  <?php include(dirname(__FILE__)."/products.php");?>

</div>


<?php
global $_CURR_CAT;
$_CURR_CAT = ($this->category->category_id*1);
?>


<?php
// --- вывод товаров из раздела "Популярные"
$currency = JTable::getInstance('currency', 'jshop');
$currency->load(2);
$this->kurs = $currency->currency_value;

$db = JFactory::getDBO();


$q = "SELECT p.*, c.category_id, cat_prod.info info, p.`name_ru-RU` title
FROM #__z_jshop_category_products AS cat_prod
LEFT JOIN #__jshopping_products AS p ON p.product_id=cat_prod.product_id
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
WHERE cat_prod.category_id=" . ($this->category->category_id*1) . "
ORDER BY cat_prod.order_by
";

$db->setQuery($q);
$items = $db->loadObjectList();


$text = "";
foreach($items AS $product)
{
    $this->product = $product;
    $item = include(JPATH_BASE."/components/com_jshopping/exe_product.php");
    $text .= "<tr><td>" . $item . "</td></tr>
                 <tr><td class=\"clear_td\"><br /></td></tr>
                 ";
}

if($text!="")
{
    echo
    "<br /><big><b>Наиболее популярные товары раздела:</b></big><br /><br />
    <table class='popular_items' >" .
    $text .
    "</table>";
}


echo $attr_razdel;
 */
?>