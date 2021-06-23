<?php
    defined('_JEXEC') or die;

	$news = (array)json_decode($params->get('dopnews'));
	$news = array_pop($news);

	$ids = implode(",", $news);
	$db = JFactory::getDBO();
	$db->setQuery("
SELECT i.*
FROM #__zoo_item AS i
WHERE (i.elements LIKE '%images%' OR i.params LIKE '%metadata.pic%') AND state=1 AND (id IN ({$ids}))
ORDER BY i.id
DESC LIMIT 30
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

		$cat = 1 * ($config->$config_id);
		if ($cat == 0)
			continue;


		$q = "SELECT alias FROM #__zoo_category WHERE id=" . $cat;
		$db->setQuery($q);
		$cat = $db->loadObject();

		$e = json_decode($n->elements);
		$text = trim($e->$text_element->$text_id->value);

		if (($img == '') || (!file_exists(JPATH_ROOT . '/images/news/' . $img))) {
			$img = substr($text, strpos($text, '<img src="') + strlen('<img src="'));
			$img = substr($img, 0, strpos($img, '"'));
			$img = str_replace("piano.by", "", $img);
			$img = '/' . str_replace("/images/", "images/", $img);
		} else {
			$img = '/images/news/' . $img;
		}

		$img = get_cache_photo($img, 300, 210, 90, 0);

		$text = trim(mb_substr(no_tags($text), 0, 200, 'utf-8'));
		$text = trim(substr($text, 0, strrpos($text, ' '))) . '...';

		$time_to_read = ceil((strlen(no_tags($text)) + 500) / 1000);
		$date = $n->publish_up;
		$i++;

		$class = "";
		if ((strpos(' ' . $text, '<iframe') > 0) || (strpos(' ' . $text, 'youtube') > 0))
			$class = ' has--video ';

        ?>
        <div class="cards__item">
            <a href="/<?php echo $cat->alias; ?>/item/<?php echo $n->alias; ?>" class="news-card news-card--third">
                <div class="news-card__top">
                    <h2><?=$n->name; ?></h2>
                </div>

                <div class="news-card__middle <?=$class;?>">
                    <img src="<?=$img; ?>" alt="" role="presentation">
                </div>
            </a>
        </div>
        <?
	}








    return;
	$cats = (array)json_decode($params->get('cats'));
	$cats = array_pop($cats);
    include_once JPATH_ROOT.'/z/functions.php';
    global $db;
?>



<? foreach ($cats AS $cat_id) { ?>
<?
    $cat_id = 1*$cat_id;
    if($cat_id<=0)  continue;
    $cat = $db->setQuery("SELECT * FROM #__jshopping_categories WHERE category_id={$cat_id}")->loadObject();
    $all = $db->setQuery("SELECT * FROM #__jshopping_categories WHERE category_parent_id={$cat_id} ORDER BY ordering ASC, category_id DESC LIMIT 6")->loadObjectList();
?>
    <div class="col-md-3 col-sm-6">
        <div class="footer-column pull-left">
            <a href="/shop/<?=$cat->{'alias_ru-RU'};?>">
                <h4>
                    <?=$cat->{'name_ru-RU'};?>
                </h4>
            </a>
            <ul class="links">
                <? foreach ($all AS $a) { ?>
                    <li><a href="/shop/<?=$a->{'alias_ru-RU'};?>" title=""><?=$a->{'name_ru-RU'};?></a></li>
                <? } ?>
            </ul>
        </div>
    </div>
<? } ?>