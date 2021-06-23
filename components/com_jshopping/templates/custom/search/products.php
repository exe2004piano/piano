<?php defined( '_JEXEC' ) or die(); ?>
<div class="jshop">
<h1><span><?php print _JSHOP_SEARCH_RESULT?> <?php if ($this->search) print '"'.$this->search.'"';?></span></h1>

<?php if (count($this->rows)){ ?>
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
</div>