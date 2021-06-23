<?php
/*------------------------------------------------------------------------
# mod_instagramslider - Facebook Widget Slider
# ------------------------------------------------------------------------
# @author - Facebook Slider
# copyright Copyright (C) 2013 FacebookSlider.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://facebookslider.com/
# Technical Support:  Forum - http://facebookslider.com/index.php/forum
-------------------------------------------------------------------------*/
// no direct access
defined( '_JEXEC' ) or die;
$document = JFactory::getDocument();
$document->addStyleSheet('modules/mod_instagramslider/assets/style.css');

$margintop = $params->get('margintop');

$ibox1_width = trim($params->get( 'iwidth' )+10);
$iheight = $params->get('iheight');
$iwidth = $params->get('iwidth');
?>
<div id="instagram_slider">
<?Php if($params->get('position')=='left'){ ?>
	<div id="ibox1" style="left: -<?php echo $ibox1_width;?>px; top: <?php echo $margintop;?>px; z-index: 10000;">
		<div id="ibox2" style="text-align: left;width:<?php echo $iwidth; ?>px;height:<?php echo $iheight; ?>px;">
			<a class="open" id="ilink" href="#"></a><img style="top: 0px;right:-50px;" src="modules/mod_instagramslider/assets/instagram-icon.png" alt="">
<?php } else { ?>
    <div id="ibox1" style="right: -<?php echo $ibox1_width;?>px; top: <?php echo $margintop;?>px; z-index: 10000;">
		<div id="ibox2" style="text-align: left;width:<?php echo $iwidth; ?>px;height:<?php echo $iheight; ?>px;">
			<a class="open" id="ilink" href="#"></a><img style="top: 0px;left:-50px;" src="modules/mod_instagramslider/assets/instagram-icon.png" alt="">
<?php } ?>						
				<iframe src="http://widget.stagram.com/in/<?php echo $params->get('instagram_id'); ?>/?s=<?php echo trim($params->get('thumbnail')); ?>&w=<?php echo trim($params->get('horizontal')); ?>&h=<?php echo trim($params->get('vertical')); ?>&b=<?php echo trim($params->get('border')); ?>&bg=<?php echo $params->get('background'); ?>&p=<?php echo trim($params->get('space')); ?>" allowtransparency="true" frameborder="0" scrolling="no" style="border:none;overflow:hidden;width:<?php echo trim($params->get('iwidth')); ?>px; height: <?php echo trim($params->get('iheight')); ?>px" ></iframe>
		</div>
		
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
						jQuery("#ibox1").hover(function(){ 
						jQuery('#ibox1').css('z-index',101009);
						<?Php if($params->get('position')=='left'){ ?>
						jQuery(this).stop(true,false).animate({left:  0}, 500); },
                        <?php } else { ?>
						jQuery(this).stop(true,false).animate({right:  0}, 500); },
						<?php } ?>	
						function(){ 
						jQuery('#ibox1').css('z-index',10000);
						<?Php if($params->get('position')=='left'){ ?>
						jQuery("#ibox1").stop(true,false).animate({left: -<?php echo $params->get( 'iwidth' )+10; ?>}, 500); });
                         <?php } else { ?>
						jQuery("#ibox1").stop(true,false).animate({right: -<?php echo $params->get( 'iwidth' )+10; ?>}, 500); });
					    <?php } ?>	
						});}); });
					</script>
