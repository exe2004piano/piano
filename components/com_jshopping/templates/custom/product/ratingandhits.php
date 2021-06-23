<?php defined( '_JEXEC' ) or die(); ?>

<div itemprop="aggregateRating"
     itemscope itemtype="http://schema.org/AggregateRating">

<?php if ($this->allow_review || $this->config->show_hits){?>
<div style="height:22px;padding:10px 0">
<table align="center" style="margin:0 auto">
<tr>
    <?php if ($this->config->show_hits){?>
    <td><?php print _JSHOP_HITS?>: </td>
    <td><?php print $this->product->hits;?></td>
    <?php } ?>

    <?php if ($this->allow_review && $this->config->show_hits){?>
    <td> | </td>
    <?php } ?>

    <?php if ($this->allow_review){?>
    <td><?php print _JSHOP_RATING?>: </td>
    <td>
    <?php print showMarkStar($this->product->average_rating);?>
    </td>
    <?php } ?>
</tr>
</table>
</div>
<?php } ?>

    <div style="display: none;">
        <span itemprop="ratingValue"><?php print $this->product->average_rating;?></span>
        <span itemprop="worstRating">0</span>
        <span itemprop="bestRating">10</span>
        <span itemprop="reviewCount"><?php print $this->product->reviews_count;?></span>
    </div>


</div>
