<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

$t =


                "<table style=\"width: 100%;\">
                    <tr><td colspan=2 style=\"text-align: center; padding-bottom: 20px;\">
                            <b class=\"prokat_title\">``{$this->prokat->title}``</b>
                    </td></tr>

                    <tr>
                        <td style=\"width: 200px;\">
                            <img src=\"/components/com_jshopping/files/img_products/{$this->prokat->image}\" style=\"width: 200px;\"/>
                        </td>
                        <td style=\"width: %; padding-left: 20px; vertical-align: top;\">
                            {$this->prokat->info}
                        </td>
                    </tr>
                    <tr>
                        <td colspan=\"2\" style=\"padding-top: 20px;\">
                            {$this->prokat->dop_info}
                        </td>
                    </tr>
                </table>


                <div class=\"clr\"> </div>

                    <table class=\"exe_product_table\" >
                        <tr>
                            <td>
			                    <span>{$this->prokat->price} / сутки</span>
							</td>
							<td>
                                <span style=\"cursor: pointer;\" class=\"button_buy\" href=\"#\" onclick=\"open_vopros({$this->prokat->product_id});\" >
	                                <img src=\"/images/prokat_button.png\" style=\"width: 102px;\"/>
                                </span>
                            </td>
                        </tr>
                    </table>

                <div class=\"clr\"> </div>

                <span style='display: none;' id=\"vopros_{$this->prokat->product_id}\">
                    Укажите телефон и мы свяжемся с Вами в течение 15 минут!<br /><br />
                    <form id=\"form_vopros_{$this->prokat->product_id}\" method='post' action='/send_vopros.php'>
                        <input type=\"hidden\" id=\"item_vopros\" name=\"item_vopros\" value=\"{$this->prokat->title}\" />
                        <input type=\"hidden\" id=\"item_price\" name=\"item_price\" value=\"{$this->prokat->price}\" />
                        <input required style='width: 200px;' type=\"text\" id=\"name_vopros\" name=\"name_vopros\" placeholder='Ваше имя' />&nbsp;&nbsp;
                        <input required style='width: 200px;' type=\"text\" id=\"phone_vopros\" name=\"phone_vopros\" placeholder='Контактный телефон' />
                        <input type=\"submit\" class=\"gray_butt\" value=\"Отправить\" />
                    </form>
                </span>
        ";



return $t;