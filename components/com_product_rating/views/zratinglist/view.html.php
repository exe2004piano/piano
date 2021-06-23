<?php
/**
 * Product_rating View for com_product_rating Component
 * 
 * @package    Product_rating
 * @subpackage com_product_rating
 * @license  GNU/GPL v2
 *
 * Created with Marco's Component Creator for Joomla! 1.6
 * http://www.mmleoni.net/joomla-component-builder
 *
 */

jimport( 'joomla.application.component.view');
jimport('joomla.application.component.model');

/**
 * HTML View class for the Product_rating Component
 *
 * @package		Product_rating
 * @subpackage	Components
 */
class Product_ratingViewZratinglist extends JViewLegacy{
	function display($tpl = null){
		$app = JFactory::getApplication();
		/*
		// load component parameters
		$params = JComponentHelper::getParams( 'com_product_rating' );
		$params = $app->getParams( 'com_product_rating' );	
		$dummy = $params->get( 'dummy_param', 1 ); 

		// load another model
		JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_product_rating/models');
		$otherModel = JModelLegacy::getInstance( 'Record', 'RecordModel' );
		*/
	
		$data = $this->get('Data');
		$this->assignRef('data', $data);
		
		$pagination = $this->get('Pagination');
		$this->assignRef('pagination', $pagination);

		parent::display($tpl);
	}
}
?>
