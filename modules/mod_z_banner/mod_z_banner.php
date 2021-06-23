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

<div class="container">
    <div class="b-news__bunner">
            <a href="<?php echo $params->get('link'); ?>" class="b-news__bunnerImg">
                <img src="/<?php echo $params->get('image'); ?>" alt="">
                <span><?php echo $params->get('button'); ?></span>
            </a>
        </div>
</div>

