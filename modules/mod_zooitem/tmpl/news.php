<?php
/**
* @package   ZOO Item
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


?>

<?php if (!empty($items)) : ?>

<ul class="latestnews">
	<?php $i = 0; foreach ($items as $item) : ?>
	<li class="latestnews"><?php echo $renderer->render('item.'.$layout, compact('item', 'params')); ?></li>
	<?php $i++; endforeach; ?>
</ul>

<?php else : ?>
<?php echo JText::_('COM_ZOO_NO_ITEMS_FOUND'); ?>
<?php endif;