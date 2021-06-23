<?php
/**
 * @copyright	Copyright (C) 2012 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * Module Slideshow CK
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

// définit la largeur du slideshow
$width = ($params->get('width') AND $params->get('width') != 'auto') ? ' style="width:' . $params->get('width') . 'px;"' : '';
$needJModal = false;
$db = JFactory::getDbo();

$q = "SELECT currency_value FROM #__jshopping_currencies WHERE currency_id=2";
$db->setQuery($q);
$res = $db->loadObject();
$kurs = 1*$res->currency_value;



if (!file_exists(JPATH_SITE.'/components/com_jshopping/jshopping.php')){
    JError::raiseError(500,"Please install component \"joomshopping\"");
}

require_once (JPATH_SITE.'/components/com_jshopping/lib/factory.php');
require_once (JPATH_SITE.'/components/com_jshopping/lib/functions.php');
JSFactory::loadCssFiles();
JSFactory::loadLanguageFile();
$jshopConfig = &JSFactory::getConfig();





?>
<!-- debut Slideshow CK -->
<div class="slideshowck<?php echo $params->get('moduleclass_sfx'); ?> camera_wrap <?php echo $params->get('skin'); ?>" id="camera_wrap_<?php echo $module->id; ?>"<?php echo $width; ?>>
	<?php
	// for ($i = 0; $i < count($items); ++$i) {
	foreach ($items as $i => $item) {

        $caption = str_replace("|dq|", "\"", $item->imgcaption);

        $products = "";
        unset($prod);

        if(strpos(" ".$caption, '[products')>0)
        {
            $prod = "-1";
            $start = strpos($caption, '[products');
            $end = strpos($caption, "]", $start);
            $products = substr($caption, $start, $end-$start+1);
            $caption = str_replace($products, "", $caption);

            $products = explode(",", str_replace(Array("[products", "]", "="), " ", $products));
            foreach($products AS $p)
                if(trim($p)*1>0)
                    $prod .= " , " . (trim($p)*1);
        }

		if ($params->get('displayorder', 'normal') == 'shuffle' && $params->get('limitslides', '') && $i >= $params->get('limitslides', ''))
			break;
		// $item = $items[$i];
		if ($item->imgalignment != 'default') {
			$dataalignment = ' data-alignment="' . $item->imgalignment . '"';
		} else {
			$dataalignment = '';
		}
		$datacaptiontitle = str_replace("|dq|", "\"", $item->imgtitle);
		$datacaptiondesc = str_replace("|dq|", "\"", $item->imgcaption);
		$datacaptionforlightbox = $datacaptiontitle . ( $datacaptiondesc ? '::' . $datacaptiondesc : '');
		$imgtarget = ($item->imgtarget == 'default') ? $params->get('imagetarget') : $item->imgtarget;
		$datatitle = ($params->get('lightboxcaption', 'caption') != 'caption') ? 'data-title="' . htmlspecialchars(str_replace("\"", "&quot;", str_replace(">", "&gt;", str_replace("<", "&lt;", $datacaptionforlightbox)))) . '" ' : '';
		$dataalbum = ($params->get('lightboxgroupalbum', '0')) ? '[albumslideshowck' .$module->id .']' : '';
		$datarel = ($imgtarget == 'lightbox') ? 'data-rel="lightbox' . $dataalbum . '" ' : '';
		$datatime = ($item->imgtime) ? ' data-time="' . $item->imgtime . '"' : '';
		if ($imgtarget == 'lightbox' && $params->get('lightboxtype', 'mediaboxck') == 'squeezebox') $needJModal = true;

		if ($params->get('articlelink', 'readmore') == 'image' && $item->article->link) {
			$item->imglink = $item->article->link;
		}
		?>
		<div <?php echo $datarel . $datatitle; ?>data-thumb="<?php echo $item->imgthumb; ?>" data-src="<?php echo $item->imgname; ?>" <?php if ($item->imglink) echo 'data-link="' . $item->imglink . '" data-target="' . $imgtarget . '"'; echo $dataalignment . $datatime; ?>>
			<?php if ($item->imgvideo) { ?>
				<iframe src="<?php echo $item->imgvideo; ?>" width="100%" height="100%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
			<?php
			}
			if (($item->imgtitle || $item->imgcaption || $item->article) && (($params->get('lightboxcaption', 'caption') != 'title' || $imgtarget != 'lightbox') || !$item->imglink)) {
			?>
				<div class="camera_caption <?php echo $params->get('captioneffect', 'moveFromBottom')?>">

                    <?php
                    if(strlen($prod)>4)
                    {
                        $q =
                            "
                            SELECT `p`.`name_ru-RU` title, c.category_id, p.image, p.product_price, p.product_id
                            FROM #__jshopping_products AS p
                            LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id = p.product_id
                            WHERE p.product_id IN ({$prod})
                            ";
                        $db->setQuery($q);
                        $res = $db->loadObjectList();
                        echo "\n\n<div class='mega_slider_div' >\n";

                        foreach($res AS $p)
                        {
                            $link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$p->category_id.'&product_id='.$p->product_id, 1);
                            $price = number_format($p->product_price * $kurs, 0, " ", " ");
                            echo "<div class=\"slide_div\">\n";
                                echo "<a href=\"" . $link . "\">\n";
                                    echo "<div>" . $p->title . "<br /><b>Всего за " . $price . " !</b></div>\n";
                                    echo "<img src='http://pianino.by/components/com_jshopping/files/img_products/" . $p->image . "' />\n";
                                echo "</a>\n";
                            echo "</div>\n";
                        }

                        echo "</div>\n\n";
                    }
                    ?>



                    <div class="camera_caption_title">
						<?php echo str_replace("|dq|", "\"", $item->imgtitle); ?>
						<?php
						if ($item->article && $params->get('showarticletitle', '1') == '1') {
							if ($params->get('articlelink', 'readmore') == 'title')
								echo '<a href="' . $item->article->link . '">';
							echo $item->article->title;
							if ($params->get('articlelink', 'readmore') == 'title')
								echo '</a>';
						}
						?>
					</div>
					<div class="camera_caption_desc">
						<?php
                            echo $caption;
                        ?>


						<?php
						if ($item->article) {
							echo $item->article->text;
							if ($params->get('articlelink', 'readmore') == 'readmore')
								echo '<a href="' . $item->article->link . '">' . JText::_('COM_CONTENT_READ_MORE_TITLE') . '</a>';
						}
						?>
					</div>
				</div>
			<?php
			}
			?>
		</div>
<?php }
if ($needJModal) JHtml::_('behavior.modal');
?>
</div>
<div style="clear:both;"></div>
<!-- fin Slideshow CK -->
