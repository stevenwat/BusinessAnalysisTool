<!-- navbar -->
<div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header" style= "width: 100%; text-align: center;">
        	<!--------------------------------New Code------------------------------->
	        <div class="container pills-container">
	  			<!-- <h3>Pills</h3>-->
	  			<ul class="nav nav-pills"  style="margin-top: 2rem; margin-bottom: 2rem;">
	  				<?php
	  				if ($access['location_access']){
	  					echo '<li class="custom-li shadow fade in" id="geoLi"><a href="#1b" data-toggle="tab" aria-expanded="true"><i class="fa fa-map" style="margin-right: 1rem;"></i>GeoLocation Services</a></li>';
	  				}
	  				if ($access['sales_access']){
	  					echo '<li class="custom-li shadow fade in" id="salesLi"><a href="#2b" data-toggle="tab" aria-expanded="true"><i class="fa fa-line-chart" style="margin-right: 1rem;"></i>Sales Overview</a></li>';
	  				}
	    			if ($access['debtor_access']){
						echo '<li class="custom-li shadow fade in" id="debtorLi"><a data-target="#3b" data-toggle="tab"><i class="fa fa-user" style="margin-right: 1rem;"></i>Debtor Analysis</a></li>';
	    			}
	    			if ($access['inventory_access']){
						echo '<li class="custom-li shadow fade in" id="invLi"><a data-target="#4b" data-toggle="tab"><i class="fa fa-clipboard" style="margin-right: 1rem;"></i>Inventory Valuation</a></li>';
					}
	    			?>
	  			</ul>
			</div>
        </div>

    </div>
</div>
<script>
$(document).ready(function(){
	/*Fixes a bug created by the fade in animation which stops the first tab from getting displayed*/
	$('.nav-pills a:last').tab('show'); 
	$('.nav-pills a:first').tab('show'); 		
});
</script>

<!-- /navbar -->