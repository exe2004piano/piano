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
 * @package	Joomla.Components
 * @subpackage	Product_rating
 */
class Product_ratingViewZrating extends JViewLegacy{
	function display($tpl = null){
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
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))){
			JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
			return false;
		}
		
		parent::display($tpl);
	}
}
?>
