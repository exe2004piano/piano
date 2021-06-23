<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


$text = explode('<hr id="system-readmore" />', $module->content);
global $z_country;
$add = "";
if($z_country=='RU')
    $add = "_ru";


?>
<div class="lazyload">
  <!-- -->
</div>
<div class="section-banner">
  <div class="section-banner__img">
    <img src='<?php echo get_webp("/".$params->get('background'.$add)); ?>' class="section-banner">
  </div>

  <div class="section-banner__content">
    <h2>Нужна консультация?</h2>
    <p>Мы специалисты в своем деле!</p>
    <h3>Звоните прямо сейчас!</h3>
    <a href="tel:+375 (44) 7-500-500" class="section-banner__phone">+375 (44) 7-500-500</a>
  </div>

</div>
<?php

/*
?>
<section class="b-advice postloader_back block_consult_div" rel='/<?php echo $params->get('background'); ?>'
  style="background-image:url('images/temp.png');">
  <!--
    <div class="container">
        <div class="row">
            <div class="col-md-5"></div>
            <div class="col-md-7">
                <div class="b-advice__content">
                    <h2 class="b-advice__title"><?php echo $module->title; ?></h2>
                    <div class="b-advice__text"><?php echo $params->get('title_text'); ?></div>
                    <div class="b-advice__call">
                        <span>Звоните сейчас:</span>
                        <a href="tel:<?php echo str_replace(Array(" ", "(", ")", "-", ".", ","), "", $params->get('title_phones'));?>" onclick="event_send('obratniy_zvonok', 'obratniyZvonok');"><?php echo $params->get('title_phones'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    -->
</section>




<section class="b-advice postloader_back block_consult_div_mobile"
  rel='/<?php echo $params->get('background_mobile'); ?>' style="background-image:url('images/temp.png');">
  <!--
        <div class="container">
            <div class="row">
                <div class="col-md-5"></div>
                <div class="col-md-7">
                    <div class="b-advice__content">
                        <h2 class="b-advice__title"><?php echo $module->title; ?></h2>
                        <div class="b-advice__text"><?php echo $params->get('title_text'); ?></div>
                        <div class="b-advice__call">
                            <span>Звоните сейчас:</span>
                            <a href="tel:<?php echo str_replace(Array(" ", "(", ")", "-", ".", ","), "", $params->get('title_phones'));?>" onclick="event_send('obratniy_zvonok', 'obratniyZvonok');"><?php echo $params->get('title_phones'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        -->
</section>



<?php
*/
    $addr = trim($_SERVER['REQUEST_URI']);
    if(strpos(" ".$addr, '?')>0)
        $addr = substr($addr, 0, strpos($addr, '?'));

// --- если мы на главной, то выведем текст
    if($addr=='/')
    {
?>

<section class="b-text">
  <div class="container">
    <?php if(strlen(trim(no_tags($text[0])))>5)
        {
        ?>
    <div class="b-text__content">
      <?php echo $text[0]; ?>
    </div>
    <div class="b-text__content b-text__content--hide">
      <?php echo $text[1]; ?>
    </div>
    <a href="#" class="b-text__showMore">Показать еще</a>
    <?php
        }
        ?>
  </div>
</section>

<?php
    }
?>