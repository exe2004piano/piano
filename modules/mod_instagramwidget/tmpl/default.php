<?php
/*------------------------------------------------------------------------
# mod_instagramwidget - Facebook Widget
# ------------------------------------------------------------------------
# @author - Joomla Widget
# copyright Copyright (C) 2013 JoomlaWidget.COM All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomlawidget.co.uk
# Technical Support:  Forum - http://www.joomlawidget.co.uk/forum
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die;
$document = & JFactory::getDocument();
$document->addStyleSheet('modules/mod_instagramwidget/assets/style.css');

$margintop = $params->get('margintop');

$ibox1_width = trim($params->get( 'iwidth' )+10);
$iheight = $params->get('iheight');
$iwidth = $params->get('iwidth');
?>
<div id="instagram_widget">
	<div id="iwidget1" style="right: -<?php echo $iwidget1_width;?>px; top: <?php echo $margintop;?>px; z-index: 10000;">
		<div id="iwidget2" style="text-align: left;width:<?php echo $iwidth; ?>px;height:<?php echo $iheight; ?>px;">
			<a class="open" id="ilink" href="#"></a><img style="top: 0px;left:-44px;" src="modules/mod_instagramwidget/assets/instagram-icon.png" alt="">
				<iframe src="http://widget.stagram.com/in/<?php echo $params->get('instagram_id'); ?>/?s=<?php echo trim($params->get('thumbnail')); ?>&w=<?php echo trim($params->get('horizontal')); ?>&h=<?php echo trim($params->get('vertical')); ?>&b=<?php echo trim($params->get('border')); ?>&bg=<?php echo $params->get('background'); ?>&p=<?php echo trim($params->get('space')); ?>" allowtransparency="true" frameborder="0" scrolling="no" style="border:none;overflow:hidden;width:<?php echo trim($params->get('iwidth')); ?>px; height: <?php echo trim($params->get('iheight')); ?>px" ></iframe>
		</div>
				<a href="http://www.garment-printer.co.uk">T Shirt Printing</a>
	</div>
</div>
<?php
	if (trim( $params->get( 'loadjquery' ) ) == 1){
	$document->addScript("http://code.jquery.com/jquery-latest.min.js");}
?>
	<script type="text/javascript">
		jQuery.noConflict();
		jQuery(function (){
			jQuery(document).ready(function()
				{
					jQuery.noConflict();
					jQuery(function (){
						jQuery("#iwidget1").hover(function(){ 
						jQuery('#iwidget1').css('z-index',101009);
						jQuery(this).stop(true,false).animate({right:  0}, 500); },
						function(){ 
						jQuery('#iwidget1').css('z-index',10000);
						jQuery("#iwidget1").stop(true,false).animate({right: -<?php echo $params->get( 'iwidth' )+10; ?>}, 500); });
						});}); });
					</script>