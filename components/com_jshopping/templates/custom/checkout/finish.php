<?php defined( '_JEXEC' ) or die(); ?>
<?php if (!empty($this->text)){?>
<?php echo $this->text;?>
<?php }else{?>
<p><?php print _JSHOP_THANK_YOU_ORDER?></p>
<p> <?php $modules = JModuleHelper::getModules('finish');
if ($modules && is_array($modules)) {
	foreach ($modules as $module) {
		echo JModuleHelper::renderModule($module);
	};
}
?>
</p>
<div class="jshop-buttons"><div id="checkout">
<a href="/">
<img src="/components/com_jshopping//images/arrow_left.gif" alt="На главную"/>
На главную
</a></div></div>
<?php }?>