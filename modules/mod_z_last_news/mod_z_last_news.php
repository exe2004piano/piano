<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$db = JFactory::getDBO();
$db->setQuery("
SELECT i.*
FROM #__zoo_item AS i
WHERE (i.elements LIKE '%images%' OR i.params LIKE '%metadata.pic%') AND state=1
ORDER BY i.id
DESC LIMIT 20
");
$news = $db->loadObjectList();
$text_element = 'aceffc38-9caa-4646-88f4-6a13710137ad';
$text_id = '0';
$config_id = 'config.primary_category';
$metadata_pic = 'metadata.pic';
$i=0;

?>


<div class="b-news">
    
        <div class="b-news__list">

            <?
				foreach($news AS $n)
				{
				    if($i>2)
				        break;

				$config = json_decode($n->params);
				$img = trim($config->$metadata_pic);

				$cat = 1*($config->$config_id);
				if($cat==0)
					continue;


				$q = "SELECT alias FROM #__zoo_category WHERE id=".$cat;
				$db->setQuery($q);
				$cat = $db->loadObject();

				$e = json_decode($n->elements);
				$text = trim($e->$text_element->$text_id->value);

				if(($img=='') || (!file_exists(JPATH_ROOT.'/images/news/'.$img)))
				{
					$img = substr($text, strpos($text, '<img src="')+strlen('<img src="'));
					$img = substr($img, 0, strpos($img, '"'));
					$img = str_replace("piano.by", "", $img);
					$img = '/' . str_replace("/images/", "images/", $img);
				}
				else
				{
					$img = '/images/news/'.$img;
				}

				$img = get_cache_photo($img, 553, 312, 90, 1);

				$text = trim(mb_substr(no_tags($text), 0, 200, 'utf-8'));
				$text = trim(substr($text, 0, strrpos($text, ' '))) . '...';

				$time_to_read = ceil((strlen(no_tags($text))+500)/1000);
				$date = $n->publish_up;
				$i++;

				$class = "";
				if(strpos(' '.$text, '<iframe')>0)
					$class = ' has--video ';

            ?>

            <div class="b-news__item">
                <a href="/<?php echo $cat->alias; ?>/item/<?php echo $n->alias; ?>" class="news-card">
                    <div class="news-card__top">
                        <h2><?=$n->name; ?></h2>
                    </div>

                    <div class="news-card__middle <?=$class;?>">
                        <div class="lazyload">
                            <!--<img src="<?=$img; ?>" alt="" role="presentation"> -->
                        </div>
                    </div>

                    <div class="news-card__bottom">
                        <div class="news-card__wrap">
                            <div class="news-card__icon">
                                <svg class="eye-icon">
                                     <use class="eye-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#eye"></use>
                                </svg>
                            </div>

                            <span><?=(1*$n->hits);?></span>
                        </div>

                        <div class="news-card__wrap">
                            <div class="news-card__icon">
                                <svg class="time-icon">
                                    <use class="time-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#time"></use>
                                </svg>
                            </div>

                            <span><?=$time_to_read;?> мин</span>
                        </div>
                    </div>
                </a>
            </div>

            <? } ?>

        </div>
        <div class="b-news__btns">
            <div class="b-news__btn">
                <a href="/novosti" class="bv-btn bv-btn--third"><span class="bv-btn__text">Все новости и статьи</span></a>
            </div>
    
            <div class="b-news__btn">
                <a href="/stati" class="bv-btn bv-btn--third"><span class="bv-btn__text">Читать статьи</span></a>
            </div>
        </div>

</div>





<?php /* 
<section class="b-news">
    <div class="container">
        <div class="b-section__title">
            <span><?php echo $module->title; ?></span>
            <a href="/novosti">Смотреть все</a>
        </div>

        <nav class="b-slider">
            <ul class="b-slider__list b-slider__list--news" data-max="5">

<?php
$db = JFactory::getDBO();
$db->setQuery("
SELECT i.*
FROM #__zoo_item AS i
WHERE (i.elements LIKE '%images%' OR i.params LIKE '%metadata.pic%') AND state=1
ORDER BY i.id
DESC LIMIT 20
");
$news = $db->loadObjectList();
$text_element = 'aceffc38-9caa-4646-88f4-6a13710137ad';
$text_id = '0';
$config_id = 'config.primary_category';
$metadata_pic = 'metadata.pic';
$i=0;
foreach($news AS $n)
{
    $config = json_decode($n->params);
    $img = trim($config->$metadata_pic);

    $cat = 1*($config->$config_id);
    if($cat==0)
        continue;

    $q = "SELECT alias FROM #__zoo_category WHERE id=".$cat;
    $db->setQuery($q);
    $cat = $db->loadObject();

    $e = json_decode($n->elements);
    $text = trim($e->$text_element->$text_id->value);

    if(($img=='') || (!file_exists(JPATH_ROOT.'/images/news/'.$img)))
    {
        $img = substr($text, strpos($text, '<img src="')+strlen('<img src="'));
        $img = substr($img, 0, strpos($img, '"'));
        $img = str_replace("piano.by", "", $img);
        $img = '/' . str_replace("/images/", "images/", $img);
    }
    else
    {
        $img = '/images/news/'.$img;
    }

    $img = get_cache_photo_200_news($img);
    // 366x256
    //$img = get_cache_photo_366_news($img);

    $text = trim(mb_substr(no_tags($text), 0, 200, 'utf-8'));
    $text = trim(substr($text, 0, strrpos($text, ' '))) . '...';

    $date = $n->publish_up;
?>
                <li class="b-slider__item">
                    <div class="b-slider__contentWrap">
                        <div class="b-slider__content">
                            <div class="b-slider__newsImg">
                                <a href="/<?php echo $cat->alias; ?>/item/<?php echo $n->alias; ?>" >
                                    <img rel="https://piano.by<?php echo $img; ?>" class="postloader_src" src="https://piano.by/images/temp.png" alt="" />
                                </a>
                            </div>
                            <span class="b-slider__date"><?php echo $date; ?></span>
                            <a href="/<?php echo $cat->alias; ?>/item/<?php echo $n->alias; ?>" class="b-slider__newsTitle">
 <?php echo $n->name; ?></a>
                            <p class="b-slider__newsText"><?php echo $text; ?></p>
                        </div>
                    </div>
                </li>
<?php
$i++;
    if($i>10)
        break;
}

?>
            </ul>
            <div class="b-slider__nav b-slider__nav--left"></div>
            <div class="b-slider__nav b-slider__nav--right"></div>
        </nav>

    </div>
</section>
*/?>

