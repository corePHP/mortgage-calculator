<?php
/**
* @version $Id: admin.mortgage.html.php
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

class HTML_accessiblity {
	
	function about ($option) {
		echo "<div style=\"text-align:center;\">";
		echo "<p><img src=\"$GLOBALS[mosConfig_live_site]/administrator/components/com_mortgage/images/corephp.gif\" alt=\"CorePHP.com logo\" /></p>";
		echo "<p style=\"text-align:center;font-size:80%;\">Contact Us at <a href=\"mailto:support@corephp.com\" >Support @ CorePHP.com</a> </p>";	
		echo "<p style=\"text-align:center;font-size:80%;\">Copyright 2006 by <a href=\"http://www.corephp.com\" >CorePHP.com</a> </p>";		
		echo "</div>";	
		echo "<input type=\"hidden\" name=\"option\" value=\"" . $option . "\" />";
		echo "<input type=\"hidden\" name=\"task\" value=\"\" />";
		return true;	
	}			
}	
?>