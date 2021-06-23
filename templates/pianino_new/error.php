<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<?php
if ($this->error->getCode() == '404') {
	echo str_replace("url_page_error", JURI::current(), file_get_contents(JURI::root().'/404'));
}
?>