<?php
/**
 * The JComments element class for Zoo
 *
 * @package JComments
 * @subpackage Plugins
 * @version 1.0
 * @author Sergey M. Litvinov (smart@joomlatune.ru)
 * @copyright (C) 2006-2010 by Sergey M. Litvinov (http://www.joomlatune.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
*/

class ElementJComments extends Element
{
	public function hasValue($params = array()) {
		return true;
	}	

	public function render($params = array())
	{
		$comments = JPATH_SITE.DS.'components'.DS.'com_jcomments'.DS.'jcomments.php';
		if (is_file($comments)) {
			require_once($comments);
			return JComments::showComments($this->_item->id, 'com_zoo', $this->_item->name);
		
		}
		return null;
	}

	public function edit()
	{
		return null;
	}
}