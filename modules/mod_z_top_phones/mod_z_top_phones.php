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


<div class="block-info">
  <a href="tel:<?php echo str_replace(Array(" ", " ", "(", ")", "-"), "",$params->get('main_phone'.$add)); ?>"
    class="block-info__text"
    onclick=" event_send('nazhal_na_telefon', 'nazhalNaTelefon'); "><?php echo $params->get('main_phone'.$add); ?></a>
  <div class="popin">
    <a href="#" class="popin__title" id="popin">
      <svg class="phone-icon phone-icon--second">
        <use class="heart-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#phone-second"></use>
      </svg>
      <span class="popin__text"><?php echo $module->title; ?></span>

      <span class="toggle-icon"></span>
    </a>

    <div class="popin__block">
      <ul class="popin__items">
        <?php
                        $all_phones = explode("\n", $params->get('all_phones'.$add));
                        foreach($all_phones AS $a)
                            if(trim($a)!='')
                            {
                                $temp = explode("=", $a);
                                ?>
        <li class="popin__item">
          <a href="tel:<?php echo str_replace(Array(" ", " ", "(", ")", "-"), "", $temp[1]); ?>" class="popin__link"
            onclick=" event_send('nazhal_na_telefon', 'nazhalNaTelefon'); ">
            <span class="popin__icon popin--icon<?php echo $temp[0]; ?>"></span>

            <span class="popin__text"><?php echo $temp[1]; ?></span>
          </a>
        </li>
        <?php
                            }
                    ?>
      </ul>

      <ul class="popin__items popin__items--second">
        <?php
                    $all_phones = explode("\n", $params->get('skype'.$add));
                    foreach($all_phones AS $a)
                        if(trim($a)!='')
                        {
                            $temp = explode("=", $a);
                            ?>
        <li class="popin__item">
          <a href="skype:<?php echo str_replace(Array(" ", " ", "(", ")", "-"), "", $temp[1]); ?>" class="popin__link">
            <span class="popin__icon">
              <svg class="skype-icon">
                <use class="skype-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#skype"></use>
              </svg>
            </span>
          </a>
        </li>
        <?php
                        }
                    ?>
      </ul>

      <div class="popin__btn">
        <a href="#" class="bv-btn" data-get-popup="call">
          <div class="bv-btn__icon">
            <svg class="phone-icon">
              <use class="phone-icon__part" xlink:href="/templates/pianino_new/i/sprite.svg#phone"></use>
            </svg>
          </div>
          <span class="bv-btn__text">Не дозвонились?</span>
        </a>
      </div>

    </div>
  </div>
</div>