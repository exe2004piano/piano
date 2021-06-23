<?php
	
	defined('JPATH_PLATFORM') or die;
?>
<div id="wishboxjshoppingproduct_<?php echo $this->field->fieldname; ?>">
	<div id="wishboxjshoppingproduct_<?php echo $this->field->fieldname; ?>_products">
		<?php if (is_array($this->field->value) && count($this->field->value)) : ?>
		<?php foreach ($this->field->value as $product_id => $product) : ?>
		<div class="portlet">
			<div class="portlet-header">
				<?php echo $product['product_name']; ?>
			</div>
			<div class="portlet-content">
				<input name="<?php echo $this->field->name; ?>[<?php echo $product_id; ?>][product_name]" type="hidden" value="<?php echo $product['product_name']; ?>" />
				<input name="<?php echo $this->field->name; ?>[<?php echo $product_id; ?>][title]" type="text" value="<?php echo $product['title']; ?>" />
			</div>
		</div>
		<?php endforeach; ?>
		<?php endif; ?>
	</div>

	<a class="btn btn-primary modal_<?php echo $this->field->id; ?>" title="Добавить товар" href="<?php echo $this->link; ?>" rel="{handler: 'iframe', size: {x: 800, y: 500}}">
		<i class="icon-new"></i>
	</a>
</div>