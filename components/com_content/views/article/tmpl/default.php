<!-- content_item -->

<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

//ini_set('log_errors', 'On');
//ini_set('error_log', 'content_item_errors.log');

defined('_JEXEC') or die;
$db = JFactory::getDBO();


JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

// Create shortcuts to some parameters.
$params  = $this->item->params;
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();
$attr_title = "";
$num_on_page = 10;
?>




<?php
// --- EXECUTER

// если в тексте нашли [FINDER] - заменим текст на результаты поиска
if(strpos(" ".$this->item->text, '[FINDER]')>0)
    $this->item->text = include(JPATH_BASE."/components/com_jshopping/finder.php");


// если в тексте нашли [BASKET] - заменим текст на результаты корзины
if(strpos(" ".$this->item->text, '[BASKET]')>0)
    $this->item->text = include(JPATH_BASE."/components/com_jshopping/basket1.php");


// если в тексте нашли [BASKET2] - заменим текст на результаты корзины
if(strpos(" ".$this->item->text, '[BASKET2]')>0)
    $this->item->text = include(JPATH_BASE."/components/com_jshopping/basket2.php");


// если в тексте нашли [THANKS_PAGE] - заменим текст на результаты корзины
if(strpos(" ".$this->item->text, '[THANKS_PAGE]')>0)
    $this->item->text = include(JPATH_BASE."/components/com_jshopping/thanks_page.php");


// если в тексте нашли [ATRIBUTES] - заменим результаты на выборку по атрибутам
if(strpos(" ".$this->item->text, '[ATRIBUTES]')>0)
    $this->item->text = include(JPATH_BASE."/components/com_jshopping/products_by_attr.php");


// если в тексте нашли [COMPARE_PRODUCTS] - заменим результаты на сравнение
if(strpos(" ".$this->item->text, '[COMPARE_PRODUCTS]')>0)
    $this->item->text = include(JPATH_BASE."/components/com_jshopping/compare.php");

// если в тексте нашли [LIKE_PRODUCTS] - заменим результаты на избранные товары
if(strpos(" ".$this->item->text, '[LIKE_PRODUCTS]')>0)
    $this->item->text = include(JPATH_BASE."/components/com_jshopping/like_products.php");

// если в тексте нашли [PRODUCTS_BRAND] - заменим результаты на выборку по бренду
if(strpos(" ".$this->item->text, '[PRODUCTS_BRAND]')>0)
    $this->item->text = include(JPATH_BASE."/components/com_jshopping/brands_products.php");

// если в тексте нашли [PRODUCTS_SALE] - заменим результаты на выборку по товарам в распродаже
if(strpos(" ".$this->item->text, '[PRODUCTS_SALE]')>0)
    $this->item->text = include(JPATH_BASE."/components/com_jshopping/sale_products.php");

// если в тексте нашли [PRODUCTS_BONUS] - заменим результаты на выборку по товарам со скидкой
if(strpos(" ".$this->item->text, '[PRODUCTS_BONUS]')>0)
    $this->item->text = include(JPATH_BASE."/components/com_jshopping/bonus_products.php");

// если в тексте нашли [PRODUCTS_RASSROCHKA] - заменим результаты на выборку по товарам с рассрочкой
if(strpos(" ".$this->item->text, '[PRODUCTS_RASSROCHKA]')>0)
    $this->item->text = include(JPATH_BASE."/components/com_jshopping/rassrochka_products.php");


// если в тексте нашли [PRODUCTS_FROM_SEARCH] - заменим результаты на выборку по товарам из произвольного поиска
if(strpos(" ".$this->item->text, '[PRODUCTS_FROM_SEARCH]')>0)
    $this->item->text = include(JPATH_BASE."/components/com_jshopping/products_from_search.php");


// --- END EXECUTER
?>













<?php if ($params->get('show_title') || $params->get('show_author')) : ?>
    <h1>
        <?php if ($this->item->state == 0) : ?>
            <span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
        <?php endif; ?>
        <?php if ($params->get('show_title')) : ?>
            <?php if ($params->get('link_titles') && !empty($this->item->readmore_link) && ($attr_title=="") ) : ?>
                <a href="<?php echo $this->item->readmore_link; ?>"> <?php echo $this->escape($this->item->title); ?></a>
            <?php else : ?>
                <?php echo $this->escape($this->item->title); ?>
            <?php endif; ?>
        <?php endif; ?>
    </h1>
<?php endif; ?>
<?php if (isset ($this->item->toc)) :

    $text1 = $this->item->toc;
    $text2 = '';
    if(strpos($text1, '<hr id="system-readmore" />')>0)
    {
        $start = strpos($text1, '<hr id="system-readmore" />') + strlen('<hr id="system-readmore" />');
        $start = strpos($text1, '</', $start);
        $start = strpos($text1, '>', $start) + strlen('>');

        $text2 = substr($text1, $start);
        $text1 = substr($text1, 0, $start);
        $text1 = str_replace('<hr id="system-readmore" />', '', $text1);

        $text2 = "<div id='hidden_readmore' >" . $text2 . "</div>";
        $text1 .= "<i id='hidden_readmore_button' onclick='jQ(\"#hidden_readmore\").toggle(300); jQ(this).toggle(300);' >Читать далее...<br /></i>";
    }

    echo $text1 . $text2 . "<br />";


endif; ?>

<?php
echo $this->item->text;
$ids = "";
$total = 0;

if( (isset($_GET['ids'])) && (isset($_GET['total'])) )
{
    $temp = explode("~", $_GET['ids']);
	
    foreach($temp AS $t)
        if(trim($t)!='')
            $ids .= "'{$t}' , ";

    $ids = substr($ids, 0, strrpos($ids, ","));
    $total = $_GET['total'];

?>

	<!--
	<script type="text/javascript">
		var _tmr = _tmr || [];
		_tmr.push({
			type: 'itemView',
			productid: [<?php echo $ids; ?>],
			pagetype: 'purchase', totalvalue: '<?php echo $total; ?>',
			list: '1' });
	</script>
	-->
<? 
} 
?>

<!-- end_content_item -->