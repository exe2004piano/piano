<?php
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


$temp = explode('/', $_SERVER['REQUEST_URI']);
$url = $temp[1];
$cur_page = 1*$temp[2];
if($cur_page==0)
    $cur_page=1;

if(strpos($url, '?')>0)
    $url = substr($url, 0, strpos($url, '?'));
?>




<div class="b-section__title">
  <h1><?php echo $this->category->name; ?></h1>
</div>

<div class="b-section__items">
  <div class="b-section__item section">
    <div class="section__row-btn">
      <a href="#" class="bv-btn bv-btn--third" id="filter-btn">
        <div class="bv-btn__icon">
          <svg class="filter-icon">
            <use class="filter-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#filter"></use>
          </svg>
        </div>
        <span class="bv-btn__text">Фильтр</span>
      </a>
    </div>

    <aside id="filter-wrap" class="section__row-filter">
      <div class="b-filter-wrap">
        <a href="#" class="bv-btn b-filter-btn" id="hide-filter">Скрыть фильтр</a>
      </div>
      <ul class="aside-list">

        <li class="aside-list__item">
          <a href="/<?php echo $url; ?>/" class="aside-list__link">Все</a>
        </li>

      </ul>


      <?php
                $db = JFactory::getDbo();
                $db->setQuery("SELECT * FROM #__zoo_tag GROUP BY name");
                if($tags = $db->loadObjectList())
                {
                    ?>
      <ul class="aside-list">
        <li class="aside-list__title">
          <span>По тегам</span>
        </li>
        <?
                foreach ($tags AS $t)
                    {
                        if( (isset($_GET['by_tag'])) && ($_GET['by_tag']==$t->name) )
                            $active = " active ";
                        else
                            $active = "";


                        ?>
        <li class="aside-list__item">
          <a href="/<?=$url;?>/?by_tag=<?=$t->name;?>" class="aside-list__link <?=$active;?>">#<?=$t->name;?></a>
        </li>
        <?php
                    }
                    ?>
      </ul>
      <?php

                }
                ?>

      <ul class="aside-list">


        <li class="aside-list__title">
          <span>По месяцам</span>
        </li>

        <?php

                $date = $this->app->date->create();
                $date = explode("-", substr($date, 0, strrpos($date, " ")));

                $select_m = 0;
                if(isset($_GET['by_date']))
                {
                    $temp = explode('-', $_GET['by_date']);
                    $select_m = 1*$temp[1];
                }

                $y = $date[0];
                $m = 1*$date[1];

                $month = Array(
                    1 => "Январь",
                    2 => "Февраль",
                    3 => "Март",
                    4 => "Апрель",
                    5 => "Май",
                    6 => "Июнь",
                    7 => "Июль",
                    8 => "Август",
                    9 => "Сентябрь",
                    10 => "Октябрь",
                    11 => "Ноябрь",
                    12 => "Декабрь"
                );


                $cur_m = $m;
                for($i=1;$i<=12;$i++)
                {
                    if($cur_m<1)
                    {
                        $cur_m = 12;
                        $y--;
                    }

                    $cur_m_print = $cur_m;
                    if($cur_m<10)
                        $cur_m_print = '0'.$cur_m;

                    if($select_m==$cur_m*1)
                        $active = " active ";
                    else
                        $active = "";

                    echo
                    '<li class="aside-list__item">
                        <a href="/'.$url.'/?by_date='.$y.'-'.$cur_m_print.'-01" class="aside-list__link '.$active.'">' . $month[$cur_m] . '</a>
                    </li>';
                    $cur_m--;
                }
                ?>
      </ul>
    </aside>

  </div>

  <div class="b-section__item items">
    <div class="items__row" id="news_list">
        <!--start_news-->
<?php

	$metadata_pic = 'metadata.pic';


	if(sizeof($this->items)==0)
	{
		JError::raiseError( 404, _JSHOP_PAGE_NOT_FOUND);
		die;
	}

	foreach($this->items AS $a)
	{
		$config = json_decode($a->params);
		$img = trim($config->$metadata_pic);

		if(!isset($a->elements))
			continue;

		if(!$el = json_decode($a->elements))
			continue;

		$text_id = '0';
		$text = '';

		foreach($el AS $e)
		{
			if( (isset($e->$text_id->value)) && (trim(strlen($e->$text_id->value))>100) )
				$text = trim($e->$text_id->value);
		}


		if(($img=='') || (!file_exists(JPATH_ROOT.'/images/news/'.$img)))
		{
			if(strpos(' '.$text, '<img src="')<1)
			{
				$img = '/images/temp_news.jpg';
			}
			else
			{
				$img = substr($text, strpos($text, '<img src="')+strlen('<img src="'));
				$img = substr($img, 0, strpos($img, '"'));
				$img = str_replace("piano.by", "", $img);
				$img = '/' . str_replace("/images/", "images/", $img);
				$img = get_cache_photo($img, 550, 310, 90, 2);
			}
		}
		else
		{
			$img = '/images/news/'.$img;
			$img = get_cache_photo($img, 553, 312, 90, 2);
		}

		$time_to_read = ceil((strlen(no_tags($text))+500)/1000);
		$link = '/' . $url . '/item/' . $a->alias;
        $name = $a->name;
        $hits = (1*$a->hits);
        include JPATH_ROOT.'/exe/zoo_novosti_item.php';
    } ?>
        <!--end_news-->
    </div>

      <?

            $temp = explode("/", $_SERVER['REQUEST_URI']);
		    $main_url = $temp[1];
		    $page = 1*$temp[2];
		    if($page<=0)
		        $page = 1;

      if ( (!isset($_GET['by_date'])) && (!isset($_GET['by_tag'])) ) { ?>
          <div class="items__btn">
              <a href="#" data-href="<?=$main_url;?>" class="bv-btn bv-btn--third" id="show_next_news" data-page="<?=$page;?>" >
                  <span class="bv-btn__text">Показать еще</span>
              </a>
          </div>
      <? } ?>

	  <?php /*
		  if ( (!isset($_GET['by_date']) && (!isset($_GET['by_tag'])) && ($pagination = $this->pagination->render($this->pagination_link)) ) )
		  {
			  ?>

              <nav class="b-section__pagination">
                  <ul class="pagination">
					  <?php
						  $temp = explode('href="', $pagination);

						  $last_page = 1*str_replace('/'.$url.'/', '', $temp[sizeof($temp)-1]);
						  for($i=1;$i<=$last_page;$i++)
						  {
							  if($i==$cur_page)
								  $active = ' class="active" ';
							  else
								  $active = '';

							  echo "<li {$active}><a href='/{$url}/{$i}'>{$i}</a></li>\n";
						  }

					  ?>
                  </ul>
              </nav>

			  <?php
		  } */
	  ?>

  </div>




  <div class="b-section__item cards">

      <? include_position("dop-news"); ?>


  </div>
</div>


