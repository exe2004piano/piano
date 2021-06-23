<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_breadcrumbs
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
?>

<div class="b-breadcrumbs">
    <ul class="b-breadcrumbs__list" itemscope itemtype="http://schema.org/BreadcrumbList" >
        <li class="b-breadcrumbs__item display-only" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
            <a class="b-breadcrumbs__link" itemprop="item" href="/">
                <span itemprop="name">
                    Главная
                </span>
                <meta itemprop="position" content="1">
            </a>
        </li>
	<?php
	// Get rid of duplicated entries on trail including home page when using multilanguage
	for ($i = 0; $i < $count; $i++)
	{
		if ($i == 1 && !empty($list[$i]->link) && !empty($list[$i - 1]->link) && $list[$i]->link == $list[$i - 1]->link)
		{
			unset($list[$i]);
		}
	}

	// Find last and penultimate items in breadcrumbs list
	end($list);
	$last_item_key   = key($list);
	prev($list);
	$penult_item_key = key($list);

	// Make a link if not the last item in the breadcrumbs
	$show_last = $params->get('showLast', 1);

	// Generate the trail
    $i=1;
	foreach ($list as $key => $item) :
        $i++;
        if(strpos($item->link, '?')>0)
            $item->link = substr($item->link, 0, strpos($item->link, '?'));
        ?>
                <li class="b-breadcrumbs__item display-only" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <? if($i<=sizeof($list)) { ?>
                    <a itemprop="item" href="<?php echo $item->link; ?>" class="b-breadcrumbs__link" >
                        <span itemscope="" itemprop="name">
                            <?php echo $item->name; ?>
                        </span>
                        <meta itemprop="position" content="<?=$i;?>">
                    </a>
                    <? } else { ?>
                    <span itemprop="item" class="b-breadcrumbs__link" >
                        <span itemprop="name">
                            <?php echo $item->name; ?>
                        </span>
                        <meta itemprop="position" content="<?=$i;?>">
                    </span>
                    <? } ?>

                </li>
	<?php
    endforeach;
    ?>
</ul>
</div>