<?php
// No direct access
defined( '_JEXEC' ) or die;
$temp = explode('<hr />', str_replace(Array("<p>", "</p>", "&nbsp;"), Array("", "", " "), $module->content));
$text = trim($temp[0]);
$text_n = trim($temp[1]);
$ot =  1*$params->get( 'ot' );
$do =  1*$params->get( 'do' );
$ot_n =  1*$params->get( 'ot_n' );
$do_n =  1*$params->get( 'do_n' );


/*
<script type="text/javascript">
    jQuery(function(){
        jQuery(window).scroll(function() {
            var top = jQuery(document).scrollTop();
            if (top > 300) jQuery('.floating').addClass('fixed').stop().slideDown("800"); //300 - это значение высоты прокрутки страницы для добавления класс
            else jQuery('.floating').removeClass('fixed');
        });
    });
</script>
*/

$h = 1*date("H");
if($h<0)
    $h=23;

if($h<7)
    $h+=24;

if
(
    (($ot<=$h) && ($do>=$h))
)
{
    ?>
    <div id="module_bottom" style="bottom: -500px;">
        <?php echo $module->content; ?>
        <div id="close_module_bottom" onclick="$('#module_bottom').css('bottom','-500px'); set_cookie('module_bottom_hidden', '1');">x</div>
    </div>
    <div id="module_bottom_opener" onclick="$('#module_bottom').css('bottom','0px'); delete_cookie('module_bottom_hidden');">$</div>
<?php

    if (!isset($_COOKIE['module_bottom_hidden']))
    {
        ?>
        <script>$('#module_bottom').css('bottom','0px');</script>
        <?php
    }
}
?>
