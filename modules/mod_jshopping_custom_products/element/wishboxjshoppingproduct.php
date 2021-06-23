<?php

	defined('JPATH_PLATFORM') or die;


	class JFormFieldWishBoxJShoppingProduct extends JFormField
	{
		public $type = 'WishBoxJShoppingProduct';
		
		
		protected function getInput()
		{
			// 
			$html = array();
			// 
			$link = 'index.php?option=com_jshopping&amp;controller=products&amp;layout=wishboxmodal&amp;tmpl=component&amp;field='.$this->id.'&amp;function=jSelectProduct_'.$this->id;
			// 
			$attr = !empty($this->class) ? ' class="' . $this->class . '"' : '';
			// 
			$attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
			// 
			$attr .= $this->required ? ' required' : '';
			// 
			JHtml::_('behavior.modal', 'a.modal_'.$this->id);
			//
			jimport('legacy.view.legacy');
			// 
			$script = new JViewLegacy();
			// 
			$script->addTemplatePath(JPATH_SITE.'/modules/mod_jshopping_custom_products/element/wishboxjshoppingproduct/tmpl');
			// 
			$script->setLayout('script');
			// 
			$script->field = $this;
			// 
			$script = $script->loadTemplate();
			// 
			JFactory::getDocument()->addCustomtag($script);
			// 
			$html = new JViewLegacy();
			// 
			$html->addTemplatePath(JPATH_SITE.'/modules/mod_jshopping_custom_products/element/wishboxjshoppingproduct/tmpl');
			// 
			$html->setLayout('html');
			// 
			$html->field = $this;
			// 
			$html->link = $link;
			// 
			$html = $html->loadTemplate();
			// 
			return $html;
		}

		
		protected function getGroups()
		{
			return null;
		}
		
		
		protected function getExcluded()
		{
			return null;
		}
	}