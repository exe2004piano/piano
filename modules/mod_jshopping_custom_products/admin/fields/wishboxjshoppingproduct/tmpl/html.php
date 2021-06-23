<?php
	
	defined('JPATH_PLATFORM') or die;
?>
<div id="wishboxjshoppingproduct_<?php echo $this->field->fieldname; ?>">
	<div class="groups_container">
		<div class="groups">
			<?php if (isset($this->field->value['groups']) && is_array($this->field->value['groups']) && count($this->field->value['groups'])) : ?>
			<?php foreach ($this->field->value['groups'] as $i => $group) : ?>
			<?php $group = (array)$group; ?>
			<div class="group_container">
				<div class="group">
					<div class="group_header">
						<?php echo $i; ?>
					</div>
					<fieldset class="group_params">
						<label>
							Заголовок:
						</label>
						<input class="input-small" name="<?php echo $this->field->name; ?>[groups][<?php echo $i; ?>][title]" type="text" value="<?php echo $group['title']; ?>" />
						<label>
							Высота:
						</label>
						<input class="input-small" name="<?php echo $this->field->name; ?>[groups][<?php echo $i; ?>][height]" type="text" value="<?php echo (!empty($group['height'])) ? $group['height'] : ''; ?>" />
						<?php
							$conf = JFactory::getConfig();
							$editor = $conf->get('editor');
							$editor = JEditor::getInstance($editor);
							echo $editor->display(
								$this->field->name.'[groups]['.$i.'][description]',
								htmlspecialchars(!empty($group['description']) ? $group['description'] : '', ENT_COMPAT, 'UTF-8'),
								300,
								300,
								30,
								30
							);
		?>
					</fieldset>
					<div class="products">
						<?php $group['products'] = !empty($group['description']) ? (array)$group['products'] : array(); ?>
						<?php if (isset($group['products']) && is_array($group['products']) && count($group['products'])) : ?>
						<?php foreach ($group['products'] as $product_id => $product) : ?>
						<?php $product = (array)$product; ?>
						<div class="portlet">
							<div class="portlet-header">
								<?php echo $product['product_name']; ?>
							</div>
							<div class="portlet-content">
								<input name="<?php echo $this->field->name; ?>[groups][<?php echo $i; ?>][products][<?php echo $product_id; ?>][product_name]" type="hidden" value="<?php echo $product['product_name']; ?>" />
								<input name="<?php echo $this->field->name; ?>[groups][<?php echo $i; ?>][products][<?php echo $product_id; ?>][title]" type="text" value="<?php echo $product['title']; ?>" />
							</div>
						</div>
						<?php endforeach; ?>
						<?php endif; ?>
					</div>
					<a class="btn btn-primary modal_<?php echo $this->field->id; ?>" title="Добавить товар" href="<?php echo $this->link; ?>&column_index=<?php echo $i; ?>" rel="{handler: 'iframe', size: {x: 800, y: 500}}">
						<i class="icon-new"></i> Добавить товар
					</a>
				</div>
			</div>
			<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
	<button class="btn add_group" type="button">
		<i class="icon-new"></i> Добавить группу
	</button>
</div>