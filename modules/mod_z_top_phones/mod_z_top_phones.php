<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


$db = JFactory::getDbo();

global $z_country;
$add = "";
if($z_country=='RU')
    $add = "_ru";

?>

<a 
onclick="event_send('nazhal_na_telefon', 'nazhalNaTelefon');" href="tel:<?php echo preg_replace("/[^0-9]/", "", $params->get('main_phone'.$add)); ?>" class="header__phone" data-toggle="dropdown">
  <span><?php echo $params->get('main_phone'.$add); ?></span>
  <svg class="dropdown-icon" width="15" height="15">
    <use href="/templates/pianino_new/public/images/sprite.svg#dropdown-icon"></use>
  </svg>
</a>
<div class="header__inf-contains" data-toggle-content="dropdown">
  <div class="header__phones">
  <?php
  $all_phones = explode("\n", $params->get('all_phones'.$add));
  $icon = '';
  foreach($all_phones AS $a) {
    if(trim($a)!='') {
        $temp = explode("=", $a);
      if ( $temp[0] == 5) {
        $icon = 'a1-icon';
      } else if ( $temp[0] == 1) {
        $icon = 'fon-icon';
      } else if ( $temp[0] == 2) {
        $icon = 'cell-icon';
      } else {
        $icon = '';
      }
    ?>
      <a href="tel:<?php echo str_replace(Array(" ", " ", "(", ")", "-"), "", $temp[1]); ?>" class="header__phone">
        <svg width="16" height="16">
          <use href="/templates/pianino_new/public/images/sprite.svg#<?php echo $icon;?>"></use>
        </svg>
        <span><?php echo $temp[1]; ?></span>
      </a>
    <?php
    }
  }
  ?>
  </div>

  <div class="header__links">
    <a class="header__link" href="skype:pianino.by">
      <svg width="32" height="32">
        <use href="/templates/pianino_new/public/images/sprite.svg#skype-icon"></use>
      </svg>
    </a>

    <a class="header__link" href="viber://chat?number=+375447500500">
      <svg width="32" height="32">
        <use href="/templates/pianino_new/public/images/sprite.svg#viber-icon"></use>
      </svg>
    </a>

    <a class="header__link" href="#">
      <svg width="32" height="32" fill="url(#telegram_linear)">
        <linearGradient id="telegram_linear" x1="33.9017" y1="8.10694" x2="21.3728" y2="38.3237" gradientUnits="userSpaceOnUse">
          <stop stop-color="#2AABEE" />
          <stop offset="1" stop-color="#229ED9" />
        </linearGradient>
        <use href="/templates/pianino_new/public/images/sprite.svg#telegram-icon"></use>
      </svg>
    </a>
  </div>
</div>

<p class="header__inf-text"><?php echo $module->title; ?></p>