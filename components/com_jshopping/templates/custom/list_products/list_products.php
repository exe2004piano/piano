<?php defined( '_JEXEC' ) or die(); 
$currency = JTable::getInstance('currency', 'jshop');
$currency->load(2);
?>
<div class="jshop list_product">
<?php foreach ($this->rows as $k=>$product){?>
<?php if ($k%$this->count_product_to_row==0) print "<div class='row'>";?>
    <div class="block_product row-<?php print $this->count_product_to_row?>">
        <?php include(dirname(__FILE__)."/".$product->template_block_product);?>
    </div>
    <?php if ($k%$this->count_product_to_row==$this->count_product_to_row-1){?>
        </div>               
    <?php }?>
<?php }?>
<?php if ($k%$this->count_product_to_row!=$this->count_product_to_row-1) print "</div>";?>
</div>