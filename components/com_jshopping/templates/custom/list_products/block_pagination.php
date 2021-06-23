<?php defined( '_JEXEC' ) or die(); ?>
<?php
    $this->pagination = str_replace("hasTooltip", "", $this->pagination);
?>

<table class="jshop_pagination">
<tr>
    <td><div class="pagination"><?php print $this->pagination?></div></td>
</tr>
</table>