<?php
/**
* @version $Id: admin.mortgage.php
* @package CorePHP.com Mortgage Component
* @copyright Copyright (C) 2005 - 2006 Steven Pignataro and Jonathan Shroyer. All rights reserved.
*
* Steven Pignataro
* Mirror Image Productions: http://www.themirrorimages.com
* CorePHP: http://www.corephp.com
*
* Jonathan Shroyer
* Design Innovations: http://www.designinnovations.net
*/

//defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( $mainframe->getPath( 'admin_html' ) );

switch($task)
{
	case "about";
		about($option);
		break;

	default:
		about($option);
	break;
}


function about($option) {
	HTML_accessiblity::about($option);
}
?>