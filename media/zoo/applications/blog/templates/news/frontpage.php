<?php
/**
* @package   com_zoo
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


// show description only if it has content
if (!$this->application->description) {
	$this->params->set('template.show_description', 0);
}
$this->app->document->addStylesheet($this->template->resource.'assets/css/frontpage.css');
// show title only if it has content
if (!$this->application->getParams()->get('content.title')) {
	$this->params->set('template.show_title', 0);
}

// show image only if an image is selected
if (!($image = $this->application->getImage('content.image'))) {
	$this->params->set('template.show_image', 0);
}

$css_class = $this->application->getGroup().'-'.$this->template->name;

?>
<div class="frontpage <?php echo $css_class; ?>">

	<?php if ($this->params->get('template.show_title') || $this->params->get('template.show_description') || $this->params->get('template.show_image')) : ?>

		<?php if ($this->params->get('template.show_title') || $this->application->getParams()->get('template.subtitle')) : ?>

			<h1 class="title"><?php echo $this->application->getParams()->get('content.title') ?></h1>

		<?php endif; ?>

		<?php if ($this->params->get('template.show_description') || $this->params->get('template.show_image')) : ?>
		<div class="frontpage-description">
			<?php if ($this->params->get('template.show_image')) : ?>
					<img class="image" src="<?php echo $image['src']; ?>" title="<?php echo $this->application->getParams()->get('content.title'); ?>" alt="<?php echo $this->application->getParams()->get('content.title'); ?>" <?php echo $image['width_height']; ?>/>
				<?php endif; ?>
			<?php if ($this->params->get('template.show_description')) echo $this->application->getText($this->application->description); ?>
		</div>
		<?php endif; ?>

	<?php endif; ?>

	<?php

		// render items
		if (count($this->items)) {
			echo $this->partial('items');
		}

	?>

</div>