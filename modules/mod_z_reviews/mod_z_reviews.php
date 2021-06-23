<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;



if(!$text = @file_get_contents($_SERVER['DOCUMENT_ROOT'].'/rev.all'))
    return;


function put_rev($r)
{
    if($r['type']=='market')
    {
        $link = '//market.yandex.ru/shop/134731/reviews';
        $site = 'YandexMarket';
    }

    if($r['type']=='onliner')
    {
        $link = '//7175.shop.onliner.by';
        $site = 'Onliner.by';
    }


    return
        '<li class="b-coment__item " >
            <h3 class="b-coment__title">
                <img class="rev_logo" src="/images/'.$r['type'].'_logo.png" /><span>'.$r['name'].'</span>
                </h3>
                <div class="b-slider__productRate">
                    <span style="width:'. ($r['ball']*20) .'%"></span>
                    <div class="b-slider__productRate-num rev_ocenka" > '.$r['ocenka'].' </div>
                </div>
                <div class="b-coment__textall" >
                        '.$r['info'].'
                </div>
                <div class="revall_link">
                    <div class="revall_data">'.$r['data'].'</div>
                    <div class="revall_url" onclick="go_to_url(\''.$link.'\');">Ссылка на отзыв на '.$site.'</div>
                </div>
            </li>';
}


$text = unserialize($text);

$echo = "";

$echo .=
'<h2 class="b-section__title b-section__title--notLink">Отзывы о Piano.by</h2>
<br />
<div id="coment" class="b-coment"><ul>';

        for($i=0; $i<2; $i++)
        {
            $r = $text[$i];
            $echo .= put_rev($r);

            $r = $text[$i+5];
            $echo .= put_rev($r);
        }
    $echo .=
        '</ul>
    </div>';

//echo "
//<script>
// 	put_text_rew('" . base64_encode($echo). "');
//</script> ";

