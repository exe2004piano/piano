<?php

// No direct access
defined( '_JEXEC' ) or die;

require_once( dirname( __FILE__ ) . '/helper.php' );
$data = modScrollHelper::getData( $params );
$moduleclass_sfx = htmlspecialchars( $params->get( 'moduleclass_sfx' ) );
require( JModuleHelper::getLayoutPath( 'mod_scroll', $params->get( 'layout', 'default' ) ) );

JPluginHelper::importPlugin('content');
$module->content = JHtml::_('content.prepare', $module->content, '', 'mod_scroll.content');
