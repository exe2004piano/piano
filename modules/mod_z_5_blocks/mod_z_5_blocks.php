<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_custom
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<section class="b-info">
  <div class="container">
    <ul class="b-info__list">
      <?php
            for($i=1; $i<=5;$i++)
            {
                $name = $params->get('name'.$i);
                $name = str_replace('[br]', '<br />', $name);
            ?>
      <li class="b-info__item">
        <div class="b-info__content">

          <svg class="b-info__icon">
            <use class="b-menu__part" xlink:href="/templates/pianino_new/i/sprite.svg#type<?php echo $i; ?>"></use>
          </svg>
          
          <h3 class="b-info__title"><?php echo $name;?></h3>
          <div class="b-info__text"><?php echo $params->get('text'.$i);?></div>
        </div>
      </li>
      <?php
            }
            ?>
    </ul>
  </div>
</section>