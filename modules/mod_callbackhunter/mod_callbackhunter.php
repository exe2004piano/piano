<?php
// No direct access
defined( '_JEXEC' ) or die;

$user = JFactory::getUser();
if($user->id!=196)
{
$ot =  9;
$do =  21;
$ot_n =  10;
$do_n =  20;

$timer =  $params->get( 'timer' );

$h = date("H")-1;	// --- текущий час 0..23, на серваке время русское +1 час в общем, надо его отнять
if($h<0) $h=23;

$w = date("w"); 	// --- текущий день недели 1..7

// --- когда будем выводить обычный текст :
if($w>=6)
{
    $ot = $ot_n;
    $do = $do_n;
}

$text = "";
// --- если текущий час больше или равен началу, но меньше финала, то это рабочее время и обычный текст :
if( ($h	>= $ot) && ($h < $do) )
    $text = $params->get( 'text' );
else
    $text = $params->get( 'text_n' );

$document 	= JFactory::getDocument();
$title = $document->getTitle();

if(strpos($title, "- Магазин")>0)
    $title = substr($title, 0, strpos($title, "- Магазин"));

?>


<script type="text/javascript" src="/js/jQueryRotateCompressed.2.2.js"></script>
<div id='my_hunter' onclick="callback_click();"></div>
<div id='my_hunter_text' onclick="callback_click();"><br />Заказать<br />звонок</div>


<?php
$phone =
"
<input type=\"text\" value=\"\" placeholder=\"Введите номер телефона\" style=\"width: 260px;\" id=\"callback_phone\" class=\"rsform-input-box\"/>
";

$time =
"<small style=\"text-decoration: underline; cursor: pointer; font-size: 0.8em!important; color: blue;\" onclick=\"toggleShow('#select_time');\">Указать время звонка</small>

                <table id=\"select_time\" style=\"display:none;\">
                    <tr>
                        <td>
                            <div class=\"select_main\">
                                <p></p>
                                <select name=\"callback_day\" id=\"callback_day\" class=\"call_back_select\">
                                    <option value=\"\">Любой день</option>
                                    <option value=\"Сегодня\">Сегодня</option>
                                    <option value=\"Завтра\">Завтра</option>
                                    <option value=\"В течение недели\">В течение недели</option>
                                </select>
                            </div>
                        </td>
                        <td style=\"width: 30px; text-align: center;\"> в </td>
                        <td>
                            <div class=\"select_main\">
                                <p></p>
                                <select name=\"callback_time\" id=\"callback_time\" class=\"call_back_select\">
                                    <option value=\"\">Любое время</option>
                                    <option value=\"10:00\">10:00</option>
                                    <option value=\"12:00\">12:00</option>
                                    <option value=\"14:00\">14:00</option>
                                    <option value=\"16:00\">16:00</option>
                                    <option value=\"18:00\">18:00</option>
                                    <option value=\"20:00\">20:00</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                </table>";



$text = str_replace(
    array("{phone}", "{time}"),
    array($phone, $time),
    $text
);

?>


<div id="callback_div" style="display:none">
    <div class="feedback">
        <span id=closed class="open" onclick="jQ('#overlay').attr('style', 'display: none;'); return toggleShow('#callback_div' ,this)"></span>
        <div class="login-module-title" style="font-size: 20px; color: #000;"><?php echo $module->title; ?></div>
        <p>
            <input type="text" style="display:none;" value="Обратный звонок со страницы: <?php echo $title; ?>" name="from_page" />
        </p>
        <div id="call_back_text" style="width: auto; margin: 0px 0px 0px 0px; font-size: 18px; text-align: center;">
            <table><tr><td style="text-align: center;">
            <?php echo $text;?>
                <a href="#" class="callback_butt" onclick="send_callbackform(); return false;">Заказать звонок</a>
                <br /><br /><br /><span id="callback_error" style="width90%; display: none; color: red;">Неправильно введен номер телефона</span>
            </td></tr>
            </table>
        </div>
    </div>
</div>

<script>

	<?php
	if(strpos("   ".$_SERVER['REQUEST_URI'], '/stati/')<1)
	{
		?>
    	var callback_times = [<?php  echo $timer; ?>];
	    <?php
    }
    else
    {
	    ?>
	    var callback_times = [-1];
    	<?php
    }
    ?>

    Array.prototype.inArray = function (value) {
        var i;
        for (i=0; i < this.length; i++) {
            if (this[i] == value) {
                return true;
            }
        }
        return false;
    };

    jQ('.call_back_select').each(function(){
        jQ(this).siblings('p').text( jQ(this).children('option:selected').text() );
    });
    jQ('.call_back_select').change(function(){
        jQ(this).siblings('p').text( jQ(this).children('option:selected').text() );
    });

</script>

<?php
}
?>




