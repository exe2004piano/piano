<?php
defined( '_JEXEC' ) or die();
// --- вывод табберов в продукт:
// ---- табберы
// tab_1 + tab_3 совместно "описание"
// tab_2 - спецификация
// tab_4 - видео

// --- проверим есть ли инструкция и скрытый текст:
$db->setQuery("SELECT p.product_ean, `p`.`name_ru-RU` title, `p`.`z_text_inv` z_text_inv, `m`.`name_ru-RU` m_title
                    FROM #__jshopping_products AS p
                    LEFT JOIN #__jshopping_manufacturers AS m ON m.manufacturer_id = p.product_manufacturer_id
                    WHERE p.product_id={$product->product_id}");
$it = $db->LoadObject();

?>



<!-- tabbers content: -->
<div class="b-detal__tabContent">
  <?php if(no_tags(trim($product->tab_1).trim($product->tab_3).trim($it->z_text_inv))!='') { ?>
  <div class="b-detal__tabContent-Item active scroll_tag" data-tabContent="1" id="tab_1">
    <div class="b-detal__aboutTitle">Описание</div>
    <div class="b-detal__tabText limited-content">

      <div class="limited-content__block">
        <?php
                    // --- выведем скрытый текст если есть:
                    if(trim(no_tags($it->z_text_inv))!='')
                    {
                        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
          put_text("<?php echo base64_encode($it->z_text_inv); ?>");
        });
        </script>
        <div id='hidden_text'></div>
        <div class='clr'></div>
        <?php
                    }
                    // --- выведем тексты которые у нас есть:
                    echo trim($product->tab_1);
                    echo "\n\n\n";
                    if(strpos($product->tab_3, '[sr_products')>0)
                        $product->tab_3 = sr_products_replace($product->tab_3, $product->product_id);

                    echo trim($product->tab_3);
                    echo "\n\n\n";
                    ?>
      </div>


      <button class="limited-content__btn">Смотреть еще</button>
    </div>
  </div>
  <?php } ?>




  <div class="b-detal__aboutWrap">
    <!-- плюсы минусы -->
    <?php include(dirname(__FILE__)."/plus_minus.php"); ?>
  </div>




  <?php if(trim($product->tab_4)!='') { ?>
  <div class="b-detal__video active scroll_tag" data-tabContent="4" id="tab_4">
    <div class="b-detal__videoTitle ">Видеообзор</div>
    <div class="b-detal__videoWrap youtube_frame">
      <?php
                // {youtube}gO6ZF-krww8{/youtube}
                    $temp = $product->tab_4;
                    if(trim($temp)!='')
                    {
                        $tt = explode("\n", $temp);
                        $i=1;
                        $video = "";
                        $videos = "";
                        foreach($tt AS $t_vid)
                            if(trim($t_vid)!='')
                            {
                                $video = explode("=", trim($t_vid));
                                if($video[0]=='')
                                    continue;

                                if($video[1]=='')
                                    $video[1] = "Видео №{$i}";

                                if($videos=="")
                                    echo '<iframe id="youtube_frame" width="100%" height="300px" src="https://www.youtube.com/embed/'.$video[0].'" frameborder="0" allowfullscreen></iframe>';
                                $videos .= "<div onclick='set_video(\"{$video[0]}\");'><i class='fa fa-youtube-play' aria-hidden='true'></i>{$video[1]}</div>";
                                $i++;
                            }
                    }

                    if($i>2)
                    {
                        echo "
                        <div id='youtube_info'>
                            <i class='fa fa-backward' aria-hidden='true'></i>
                            <br />
                            {$videos}
                        </div>
                    ";
                    }

                ?>
    </div>
  </div>
  <?php } ?>

  <?php if(trim($product->tab_5)!='') { ?>
  <script src="/player/player.js"></script>
  <link href="/player/player.css" rel="stylesheet" />

  <div class="mediatec-cleanaudioplayer">
    <ul data-theme="default">
      <?
                $temp = explode("\n", trim($product->tab_5));
                foreach($temp AS $t)
                {
                    if(trim($t)=='')
                        continue;
                    $mp3 = explode('=', $t);
                    ?>
      <li data-title="<?=trim($mp3[0]);?>" data-type="mp3" data-url="<?=trim($mp3[1]);?>" data-free="true"></li>
      <?
                }
                ?>
    </ul>
  </div>
  <br />
  <? } ?>


    <? include_once __DIR__.'/filter_products.php'; ?>







    <div class="blocks">

		<? if(trim($product->tab_6)!='') { // --- инструкции ?>
            <div class="blocks__block">
                <p class="blocks__block-title">Инструкции и сертификаты</p>
                <div class="documents">
                    <? $instr = explode("\n", trim($product->tab_6));
                        foreach ($instr AS $ins) { if(trim($ins)=='') continue; $temp = explode("=", $ins); ?>
                            <div class="documents__item">
                                <a target="_blank"
                                   href="<?=trim($temp[1]);?>"
                                   class="documents__link">
                                    <svg class="document__icon" width="25" height="25">
                                        <use class="libra-icon__part" href="/templates/pianino_new/i/sprite.svg#pdf"></use>
                                    </svg>
                                    <span class="document__text"><?=trim($temp[0]);?></span>
                                </a>
                            </div>
						<? } ?>
                </div>
            </div>
        <? } ?>



        <? if(trim($product->tab_2)!='') { ?>
            <div class="blocks__block" id="characteristics_block">
                <p class="blocks__block-title">Характеристики</p>
                <div class="tbl-block">
                    <?
                    if(strpos("   ".substr($product->tab_2, 0, 5), '!')<1)
                        $product->tab_2 = "!Основные характеристики\n" . $product->tab_2;

                    $temp = explode("\n", $product->tab_2);
                    $tab2 = Array();
                    $current_block = "";
                    foreach ($temp AS $t)
                    {
						if(strpos(" ".$t, '!')>0)
                        {
                            $t = str_replace("!", "", $t);
							$tab2[$t] = Array();
							$current_block = $t;
							continue;
                        }

                        $chars = explode("=", $t);
						$tab2[$current_block][$chars[0]]=$chars[1];
                    }
                    ?>

                    <? $data_show = ""; $i=0; ?>
                    <? foreach ($tab2 AS $main_title=>$elements) { $i++; ?>
                        <div class="tbl-block__row" <?=$data_show;?>>
                            <p class="tbl-block__title"><?=$main_title;?></p>

                            <? foreach ($elements AS $title=>$value) { ?>
                            <div class="tbl-block__td">
                                <div class="tbl-block__cols">
                                    <p class="tbl-block__col"><?=$title;?></p>
                                    <p class="tbl-block__col"><?=$value;?></p>
                                </div>
                            </div>
                            <? } ?>
                        </div>
                        <? if($i>1) $data_show = ' data-show="false" '; ?>
                    <? } ?>

                    <? if(sizeof($tab2)>1) { ?>
                        <a class="tbl-block__btn">Подробные характеристики</a>
                    <? } ?>

                </div>
            </div>
        <? } ?>
    </div>


</div>
<!-- END tabbers content -->