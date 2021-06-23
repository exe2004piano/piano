<?php defined( '_JEXEC' ) or die();
$categorylink = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id='.$this->category->category_id);
if (JRequest::getInt('limitstart') > 0) $categorylink .= "?start=" . JRequest::getVar('limitstart');
// Executer: уберем ограничение на линк
// if ($categorylink!= JRequest::getURI() && JRequest::getVar('controller') != "search") JApplication::redirect($categorylink,'','' ,true);

?>
<?php if ($this->display_list_products){?>
<div class="jshop_list_product">

<?php
    include(dirname(__FILE__)."/../".$this->template_block_form_filter);
    if (count($this->rows)){
        include(dirname(__FILE__)."/../".$this->template_block_list_product);
    }
    if ($this->display_pagination){
        include(dirname(__FILE__)."/../".$this->template_block_pagination);
    }
?>
</div>
<?php }?>