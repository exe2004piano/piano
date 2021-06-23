<?php
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
$db = JFactory::getDBO();

?>




<div class="b-main__review">
    <?php if ($this->checkPosition('title')) : ?>
        <h1 class="b-main__reviewTitle"><?php echo no_tags($this->renderPosition('title')); ?></h1>
    <?php endif; ?>


    <div class="b-main__reviewBlockc-ontent">
        <div class="b-main__reviewContent clearFix">
            <?php if ($this->checkPosition('fulltext')) : ?>
                <?php echo $this->renderPosition('fulltext'); ?>
            <?php endif; ?>
        </div>
    </div>
</div>








<?php

$q = "
SELECT p.*, p.`name_ru-RU` title, c.category_id, l.`name` label_name
FROM #__z_zoo_to_products AS z
LEFT JOIN #__jshopping_products AS p ON p.product_id=z.product_id
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id=p.product_id
LEFT JOIN #__jshopping_product_labels AS l ON p.label_id=l.id
WHERE z.zoo_id={$this->_item->id} AND p.product_publish=1 AND p.product_price>0
ORDER BY z.id
DESC LIMIT 10
";

$db->setQuery($q);
if($products = $db->loadObjectList())
{
    echo "<!--";

    foreach($products AS $p)
        echo $p->product_id . "---";

    echo "-->";

?>
<section class="b-section__like b-section__like--thisReview">
    <div class="container">
        <h2 class="b-section__title b-section__title--notLink">Товары из этого обзора</h2>
        <nav class="b-slider">
            <ul class="b-slider__list" data-max="5" data-width="220">
<?php

    foreach($products AS $product)
    {

        echo include(JPATH_ROOT.'/components/com_jshopping/get_label_products.php');
    }
    ?>
            </ul>
            <div class="b-slider__nav b-slider__nav--left"></div>
            <div class="b-slider__nav b-slider__nav--right"></div>
        </nav>
    </div>
</section>

<?php
}
?>





<?php
$cat_id = $this->_item->params['config.primary_category'];
?>










<?php
$db = JFactory::getDBO();
$db->setQuery("
SELECT i.*
FROM #__zoo_item AS i
WHERE i.elements LIKE '%images%' AND i.params LIKE '%\"config.primary_category\": \"{$cat_id}\"%' AND i.id<>{$this->_item->id}
ORDER BY i.id DESC
LIMIT 5
");

if($news = $db->loadObjectList())
{
$text_element = 'aceffc38-9caa-4646-88f4-6a13710137ad';
$text_id = '0';
$config_id = 'config.primary_category';
$metadata_pic = 'metadata.pic';
$i=0;
?>
<section class="b-news b-news--readMore">
    <div class="container">
        <div class="b-section__title">
            <span>Читайте так же</span>
        </div>
        <nav class="b-slider">
            <ul class="b-slider__list b-slider__list--news" data-max="5">

<?php
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

                    $img =  get_cache_photo($img, 290, 160, 100, 1);

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
                                <a href="/<?php echo $cat->alias; ?>/item/<?php echo $n->alias; ?>" class="b-slider__newsTitle"><?php echo $n->name; ?></a>
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
<?php
}
?>