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


?>

<div class="b-main__reviewContent">
	<?php if ($this->checkPosition('title')) : ?>
	<h1 class="title"><span><?php echo $this->renderPosition('title'); ?></span></h1>
	<?php endif; ?>

	<?php if ($this->checkPosition('content')) : ?>
	<div class="item-desc">
		<?php
            $t = vopros_otvet_replace($this->renderPosition('content'));
            echo $t;
        ?>
	</div>
	<?php endif; ?>
</div>