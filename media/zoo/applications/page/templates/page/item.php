<?php
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$css_class = $this->application->getGroup().'-'.$this->template->name;

?>


	<?php if ($this->renderer->pathExists('item/'.$this->item->type)) : ?>
	<div class="<?php echo $this->item->type;?>">
		<?php echo $this->renderer->render('item.'.$this->item->type.'.full', array('view' => $this, 'item' => $this->item)); ?>
		<?php echo $this->app->comment->renderComments($this, $this->item); ?>
	</div>
	<?php else : ?>
	<div class="item content">
		<?php echo $this->renderer->render('item.full', array('view' => $this, 'item' => $this->item)); ?>
		<?php echo $this->app->comment->renderComments($this, $this->item); ?>
	</div>
	<?php endif; ?>

