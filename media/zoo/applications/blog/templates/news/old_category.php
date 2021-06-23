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


?>




<div class="b-section__title b-section__title--notLink">
    <span><?php echo $this->category->name; ?></span>
</div>


<div class="row" style="margin-top: 30px;">


    <div class="col-sm-2">
        <nav class="b-main__menu js_listWrap">
            <button class="b-main__menuButton js_listLink">По месяцам</button>
            <ul class="b-main__menuList js_listBlock">

                <li class="b-main__menuItem">
                    <a href="/<?php echo $url; ?>/" class="b-main__menuLink">Все</a>
                </li>

                <li class="b-main__menuItem">
                    <br />
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
                    '<li class="b-main__menuItem ">
                        <a href="/'.$url.'/?by_date='.$y.'-'.$cur_m_print.'-01" class="b-main__menuLink '.$active.'">' . $month[$cur_m] . '</a>
                    </li>';
                    $cur_m--;
                }
                ?>
            </ul>
        </nav>
    </div>



    <div class="col-sm-10">
        <nav class="b-main__article">
            <ul class="b-main__articleList">

            <?php

                $metadata_pic = 'metadata.pic';


                if(sizeof($this->items)==0)
                {                	JError::raiseError( 404, _JSHOP_PAGE_NOT_FOUND);
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
                            $img = get_cache_photo_200_news($img);
                        }
                    }
                    else
                    {
                        $img = '/images/news/'.$img;
                        $img = get_cache_photo_200_news($img);
                    }



                    ?>

                    <li class="b-main__articleItem">
                        <div class="b-main__articleContent">
                            <div class="b-main__articleImg">
                                <a href="<?php echo '/' . $url . '/item/' . $a->alias; ?>">
                                    <img src="<?php echo $img;?>" alt="">
                                </a>
                            </div>
                            <a href="<?php echo '/' . $url . '/item/' . $a->alias; ?>" class="b-main__articleLink">
                                <span><?php echo $a->name . " &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;"; ?></span>
                            </a>
                        </div>
                    </li>

                <?php
                }

                ?>
            </ul>
        </nav>

        <div class="clr"> </div>


    <?php


    if ( (!isset($_GET['by_date']) && ($pagination = $this->pagination->render($this->pagination_link)) ) )
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
    }
    ?>

    </div>
</div>

<div class="clr"> </div>

