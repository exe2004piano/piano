<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
	<div style="position:absolute; margin-top: -10px; margin-left: -5px;">
        <a href="#" onclick="jQ('#Client\\ Name').val('Помогите с выбором товара'); return toggleShow('#feedback-bg' ,this); " class="tab_butt">
        <table >
            <tr>
                <td>
                    <img src="images/operator.png" style="width: 45px;"/>
                </td>
                <td>
                    <center><b>Задать вопрос<br /> специалисту</b></center>
                </td>
            </tr>
        </table>
        </a>
    </div>
    <br /><br /><br /><br />



	<div class="filter-field-price">
		<span class="h3_1">
			<?php echo JText::_('MOD_JSHOP_EFILTER_FIELDS_PRODUCT_PRICE_TITLE'); ?>
		</span>

<?php
$price_from = 1*JRequest::getVar('price-from');
$price_to = 1*JRequest::getVar('price-to');
if($price_from==0)
	$price_from = "0";

if($price_to==0)
	$price_to = "100000000";

?>
		<input class="inputbox my_input" style="width: 40%; max-width: 100px;" name="price-from" id="price_from" type="text" <?php echo ' value="'.$price_from.'"'; ?> /> -
		<input class="inputbox my_input" style="width: 40%; max-width: 100px;" name="price-to" id="price_to" type="text" <?php echo ' value="'.$price_to.'"'; ?> />
	</div>
<br />
<div id="slider"></div>
<sup style="font-size: 0.7em;">
<table style="width: 100%;">
	<tr>
		<td style="text-align:left;">0</td><td style="text-align:center;">25m</td><td style="text-align:right;">50m</td><td style="text-align:right;">75m</td><td style="text-align:right;">100m</td>
	</tr>
</table>
</sup>

<script type="text/javascript">

jQuery("#slider").slider({
    min: 0,
    max: 100,
    step: 1,
    values: [0,100],
    range: true,
    stop: function(event, ui) {
        jQuery("input#price_from").val( (jQuery("#slider").slider("values",0) * 1000000) );
        jQuery("input#price_to").val( (jQuery("#slider").slider("values",1) * 1000000) );
    },
    slide: function(event, ui){
        jQuery("input#price_from").val( (jQuery("#slider").slider("values",0) * 1000000) );
        jQuery("input#price_to").val( (jQuery("#slider").slider("values",1) * 1000000) );
    }
});


	jQuery("input#price_from").change(function(){
    var value1 = jQuery("input#price_from").val()/1000000;
    var value2 = jQuery("input#price_to").val()/1000000;
    if(parseInt(value1) > parseInt(value2)){
        value1 = value2;
        jQuery("input#price_from").val(value1 * 1000000);
    }
    jQuery("#slider").slider("values",0,value1);
});


jQuery("input#price_to").change(function(){
    var value1=jQuery("input#price_from").val()/1000000;
    var value2=jQuery("input#price_to").val()/1000000;
    if (value2 > 100) { value2 = 100; jQuery("input#price_to").val(100 * 1000000)}
    if(parseInt(value1) > parseInt(value2)){
        value2 = value1;
        jQuery("input#price_to").val(value2 * 1000000);
    }
    jQuery("#slider").slider("values",1,value2);
});

	jQuery("input#price_from").change();
	jQuery("input#price_to").change();

</script>
