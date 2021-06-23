<?php // no direct access
defined('_JEXEC') or die('Restricted access'); 
$data = $this->data;
$link = JRoute::_( "index.php?option=com_product_rating&view=zrating&id={$data->id}" );
?>
<div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'COM_PRODUCT_RATING_ZRATING_ID_LABEL' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->id; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'COM_PRODUCT_RATING_ZRATING_CAT_ID_LABEL' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->cat_id; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'COM_PRODUCT_RATING_ZRATING_ALIAS_LABEL' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->alias; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'COM_PRODUCT_RATING_ZRATING_ACTIVE_LABEL' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->active; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'COM_PRODUCT_RATING_ZRATING_TITLE_LABEL' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->title; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'COM_PRODUCT_RATING_ZRATING_H1_LABEL' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->h1; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'COM_PRODUCT_RATING_ZRATING_META_DESC_LABEL' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->meta_desc; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'COM_PRODUCT_RATING_ZRATING_DESCRIPTION_LABEL' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->description; ?></span>
	</div>
	<div class="jcb_fieldDiv">
		<span class="jcb_fieldLabel"><?php echo JText::_( 'COM_PRODUCT_RATING_ZRATING_PARAMS_LABEL' ); ?></span>
		<span class="jcb_fieldValue"><?php echo $data->params; ?></span>
	</div>

</div>
