<?php defined( '_JEXEC' ) or die(); ?>
<script type="text/javascript">var liveurl = '<?php print JURI::root()?>';</script>
<div class="jshop page-search">
    <h1><span><?php print _JSHOP_SEARCH ?></span></h1>
    
    <form action="<?php print $this->action?>" name="form_ad_search" method="post" onsubmit="return validateFormAdvancedSearch('form_ad_search')">
    <input type="hidden" name="setsearchdata" value="1">
    <table class = "jshop" cellpadding = "6" cellspacing="0">
      <?php print $this->_tmp_ext_search_html_start;?>
      <tr>
  	    <td width="120">
  		    <?php print _JSHOP_SEARCH_TEXT?>
	    </td>
        <td>
          <input type = "text" name = "search" class = "inputbox search-field" style = "width:300px" />
        </td>
      </tr>
      <tr>
          <td width="120">
              <?php print _JSHOP_SEARCH_FOR?>
        </td>
        <td>
          <input type="radio" name="search_type" value="any" id="search_type_any" checked="checked" /> <label for="search_type_any"><?php print _JSHOP_ANY_WORDS?></label>
          <input type="radio" name="search_type" value="all" id="search_type_all" /> <label for="search_type_all"><?php print _JSHOP_ALL_WORDS?></label>
          <input type="radio" name="search_type" value="exact" id="search_type_exact" /> <label for="search_type_exact"><?php print _JSHOP_EXACT_WORDS?></label>
        </td>
      </tr>
      <tr>
        <td>
          <?php print _JSHOP_SEARCH_CATEGORIES ?>
        </td>
        <td> 
          <?php print $this->list_categories ?><br />
          <input type = "checkbox" name = "include_subcat" id = "include_subcat" value = "1" />
          <label for = "include_subcat"><?php print _JSHOP_SEARCH_INCLUDE_SUBCAT ?></label>
        </td>
      </tr>
      <tr>
        <td>
          <?php print _JSHOP_SEARCH_MANUFACTURERS ?>    
        </td>
        <td>
          <?php print $this->list_manufacturers ?>
        </td>
      </tr>
      <?php if (getDisplayPriceShop()){?>
      <tr class="price">
        <td>
          <?php print _JSHOP_SEARCH_PRICE_FROM ?>      
        </td>
        <td>
          <input type = "text" class = "inputbox" name = "price_from" id = "price_from" /> <?php print $this->config->currency_code?>
        </td>
      </tr>
      <tr class="price">
        <td>
          <?php print _JSHOP_SEARCH_PRICE_TO ?>      
        </td>
        <td>
          <input type = "text" class = "inputbox" name = "price_to" id = "price_to" /> <?php print $this->config->currency_code?>
        </td>
      </tr>
      <?php }?>
      <tr>
        <td colspan="2" id="list_characteristics"><?php print $this->characteristics?></td>
      </tr>
      <?php print $this->_tmp_ext_search_html_end;?>
    </table>    
    <div style="padding:6px;">
    <input type = "submit" class="button" value = "<?php print _JSHOP_SEARCH ?>" />  
    </div>
    </form>
</div>