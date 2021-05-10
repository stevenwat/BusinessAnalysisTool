<?php
include_once "config/core.php";			// core configuration
//include_once "login_checker.php";		// check if logged in as admin
$page_title = "Location Services";
$access = array("location_access"=>false, "sales_access"=> false, "debtor_access"=> false, "inventory_acccess"=>false);
$active_tab = '';
include_once 'config/database.php';
include_once 'objects/useraccess.php';
include_once "authentication.php";
include_once "layout_head.php";			// include page header HTML and navivation bar and buttons
include_once 'objects/customerlocations.php';
include_once 'objects/salesMtd.php';
include_once 'objects/inventorytotals.php';
include_once 'objects/debtors.php';


echo '<div class="tab-content clearfix">';
if ($access['sales_access']){
	echo '	<div class="tab-pane fade" id ="2b">';
	include "salesOverview.php";
	echo '  </div>';
}
if ($access['debtor_access']){
	echo '  <div class="tab-pane fade" id ="3b">';
	include "debtorAnalysis.php";
	echo '	</div>';	
}
if ($access['inventory_access']){
	echo '	<div class="tab-pane fade" id ="4b">';
	include "inventoryAnaylsis.php";
	echo '	</div>';
}
if ($access['location_access']){
	echo '	<div class="tab-pane fade" id ="1b">';
	include "geoLocationServices.php";
	echo '	</div>';	
}
echo '</div>';
 		
//}
echo "</div>";
// Include page footer HTML
include_once "layout_foot.php";
?>
