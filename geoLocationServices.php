<table style= "table-layout: fixed; width: 100%;">
	<tr>
    	<th style="text-align: center;"><image class="marker-image" id="Go"></image></th>
    	<th style="text-align: center;"><image class="marker-image" id="Middys"></image></th>
    	<th style="text-align: center;"><image class="marker-image" id="TLE"></image></th>
    	<th style="text-align: center;"><image class="marker-image" id="Rexel"></image></th>
    	<!-- <th style="text-align: center;"><i class="fas fa-users fa-3x icon-style" style="font-style: normal;"></i></th>-->
    	<th style="text-align: center;"><i class="fa fa-user icon-style" id="icon-customers"></i></th>
    	<!-- <th style="text-align: center;"><i class="fa fa-copy icon-style"></i></th>-->
    	<th style="text-align: center;"><image class="marker-image" id="Haymans"></image></th>
    	<th style="text-align: center;"><image class="marker-image" id="JohnTurk"></image></th>
    	<th style="text-align: center;"><image class="marker-image" id="MMEM"></image></th>
    	<th style="text-align: center;"><i class="fa fa-address-card icon-style" id="icon-prospects"></i></th>
    </tr>
    <tr>
    	<td><input type="checkbox" id="GoCheckBox" class="image-checkbox" onchange="reloadMap()"></input></td>
    	<td><input type="checkbox" id="MiddysCheckBox" class="image-checkbox" onchange="reloadMap()"></input></td>
    	<td><input type="checkbox" id="TLECheckBox" class="image-checkbox" onchange="reloadMap()"></input></td>
    	<td><input type="checkbox" id="RexelCheckBox" class="image-checkbox" onchange="reloadMap()"></input></td>
    	<td><input type="checkbox" id="CustomersCheckBox" class="image-checkbox" onchange="reloadMap()"></input></td>
    	<td><input type="checkbox" id="HaymansCheckBox" class="image-checkbox" onchange="reloadMap()"></input></td>
    	<td><input type="checkbox" id="JohnTurkCheckBox" class="image-checkbox" onchange="reloadMap()"></input></td>
    	<td><input type="checkbox" id="MmemCheckBox" class="image-checkbox" onchange="reloadMap()"></input></td>
    	<td><input type="checkbox" id="ProspectsCheckBox" class="image-checkbox" onchange="reloadMap()"></input></td>
  	</tr>  
</table>

<!---------------------------------------------------->
	
<div id='googleMap' style='height:650px;width:100%; margin-top:5em; border-radius: 10px;'></div>
<br>
<?php
	include_once 'objects/wholesalerlocations.php';
	include_once 'objects/prospectinfo.php';
	include_once 'objects/salesMtd.php';
	 
	// Get database connection
	$database = new Database();
	$db = $database->getConnection();
	
	// Initialize objects
	$custlocation = new CustomerLocations($db);
	$currentLocations = array();
	
	$whlocation = new WholesalerLocations($db);
	$whLocations = array();
	
	$prospectLocation = new ProspectLocations($db);
	$prospectLocations = array();
	
	echo "<div class='col-md-12'>";
		
	// Read locations from the database, for pagination (displaying records in the table)
	// Read locations from the database to display the google markers (grab every location within a postcode)	
	$stmtGrabAll = $custlocation->grabCurrentWithoutLimit();
	$stmtGrabWholesalers = $whlocation->grabCurrentWithoutLimit();
	$stmtGrabProspects = $prospectLocation->grabCurrentWithoutLimit();

	while ($row = $stmtGrabProspects->fetch(PDO::FETCH_ASSOC)){
		$prospectLocations[] = $row;
	}	

	$page_url="index_new.php?";
		// Potential data Id, HD Name, Email, State, Suburb, Address, Phone, Postcode, Granularity, Latitude, Longitude, Website URl, BusinessType
		$tableHeader = array("deb_acc","name","latitude", "longitude");
		echo "<br>";
		while ($row = $stmtGrabAll->fetch(PDO::FETCH_ASSOC)){	
			$currentLocations[] = $row;
		}
			
		
		$whTableHeader = array("wholesaler_name","branch_abbrev","latitude", "longitude");
		while ($row = $stmtGrabWholesalers->fetch(PDO::FETCH_ASSOC)){
			// For each row extract the key and its associated value
			// Add the element to the list
			$whLocations[] = $row;
		}		
		// The currentLocations variable is used to transfer the data from php to javascript, so when the google maps object is created
		// the geopoints of the hd locations will use this data
		//error_log($row[1][1], 3, "C://xampp//php//logs//test4.log");
		
		$page_url="index_new.php?";
	?>
	
	<script>
	/*Should only be run once each page load*/
	document.getElementById("GoCheckBox").checked = true;
	document.getElementById("MiddysCheckBox").checked = false;
	document.getElementById("TLECheckBox").checked = false;
	document.getElementById("RexelCheckBox").checked = false;
	document.getElementById("CustomersCheckBox").checked = false;
	document.getElementById("HaymansCheckBox").checked = false;
	document.getElementById("JohnTurkCheckBox").checked = false;
	document.getElementById("MmemCheckBox").checked = false;
	document.getElementById("ProspectsCheckBox").checked = false;
	
	function reloadMap(){
		//Set latitude and longitude from php postcode query if the user has preferred to use their postcode 
		// Potential data Id, HD Name, Email, State, Suburb, Address, Phone, Postcode, Granularity, Latitude, Longitude, WebsiteUrl, BusinessType
		var map, infoWindow, pos;
		var locations = [];
		var whLocations = [];
		var proLocations = [];
		
		<?php 
			// Error_log("Test", 3, "C://xampp//php//logs//test.log");
			foreach($currentLocations as $a){ ?>
				//error_log("test123", 3, "C://xampp//php//logs//test3.log");
				//error_log($a['latitude']. " ". $a['longitude']. " ", 3, "C://xampp//php//logs//test2.log");?>
				locations.push({
					lat: Number("<?=$a['latitude'];?>"), 
					lng: Number("<?=$a['longitude'];?>"), 
					hdName: "<?=$a['name'];?>"});
		<?php } 
			foreach($whLocations as $b){ ?>
				whLocations.push({
					lat: Number("<?=$b['latitude'];?>"), 
					lng: Number("<?=$b['longitude'];?>"), 
					hdName: "<?=$b['branch_abbrev'];?>",
					address: "<?=$b['address'];?>",							//New line added here and in the wholesalerlocations.php file-in the SQL statement
					storename: "<?=$b['store_name'];?>",
					phone: "<?=$b['phone'];?>",
					email: "<?=$b['email'];?>",
					website: "<?=$b['website'];?>",
					manager: "<?=$b['manager'];?>",					
					wholeSalerName:  "<?=$b['wholesaler_name'];?>"});
		<?php }?>	
		/*var temp = "";
		for (temp in whLocations){
			console.log(temp);
		}*/
		<?php 
			foreach($prospectLocations as $c){ ?>
				proLocations.push({
					lat: Number("<?=$c['latitude'];?>"), 
					lng: Number("<?=$c['longitude'];?>"), 
					prospectName: "<?=$c['name'];?>",
					address:  "<?=$c['address'];?>"});
		<?php }?>	
		
		var myCenter = new google.maps.LatLng(-33.870, 151.210);
		map = new google.maps.Map(document.getElementById('googleMap'), {
	    	zoom: 10,
	    	center: myCenter,
	    	mapTypeId: google.maps.MapTypeId.ROADMAP
	    });

		var infowindow = new google.maps.InfoWindow();
		var marker, i;
		var markers =[];

		//Adds Customer Locations 
	    for (i = 0; i < locations.length; i++) {  
	    	if (document.getElementById("CustomersCheckBox").checked){
				marker = new google.maps.Marker({
			    	position: new google.maps.LatLng(locations[i]['lat'], locations[i]['lng']),
			    	animation: google.maps.Animation.DROP,
			    	visible: false,							//Change here to remove the cluster
			    	//label: locations[i]['hdName'],
			    	map: map
			   	});
				markers.push(marker);				//Change here to remove the cluster
	    	}
	    }		

		//Add Prospect Locations (SA, NT, WA, TAS) 	
	    for (i = 0; i < proLocations.length; i++){
	    	if(document.getElementById("ProspectsCheckBox").checked){
				marker = new google.maps.Marker({
			    	position: new google.maps.LatLng(proLocations[i]['lat'], proLocations[i]['lng']),
			    	animation: google.maps.Animation.DROP,
			    	visible: false,							//Change here to remove the cluster
			    	//label: locations[i]['hdName'],
			    	map: map
			   	});
				markers.push(marker);		        
			}			      
	    }
	    
	    var markerCluster = new MarkerClusterer(map, markers,
	            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});		//Change here to remove the cluster
	    /*Wholesaler markers*/        
		var whMarker, j;
		var whMarkers =[];
		var infowindow = new google.maps.InfoWindow();
		var image = 'resources/map_marker_goelectrical.png';
		for (j = 0; j < whLocations.length; j++) {  
			//set the Market variable based on the wholesaler type retrieved from the database
			var tempImage = "";
			var createMarker = true;
			switch(whLocations[j]['wholeSalerName']){
				case "GO":
					tempImage = "resources/map_marker_goelectrical.png";
					if(!document.getElementById("GoCheckBox").checked){
						createMarker = false;
					}	
					break; 
				case "TLE":
					if(!document.getElementById("TLECheckBox").checked){
						createMarker = false;
					}						
					tempImage = "resources/map_marker_tle2.png";	
					break;
				case "Middys":
					if(!document.getElementById("MiddysCheckBox").checked){
						createMarker = false;
					}	
					tempImage =  "resources/map_marker_middy.png";	
					break;
				case "Rexel":
					if(!document.getElementById("RexelCheckBox").checked){
						createMarker = false;
					}	
					tempImage =  "resources/map_marker_rexel.png";	
					break;	
				case "Haymans":
					if(!document.getElementById("HaymansCheckBox").checked){
						createMarker = false;
					}	
					tempImage =  "resources/map_marker_haymans.png";	
					break;	
				case "John R Turk":
					if(!document.getElementById("JohnTurkCheckBox").checked){
						createMarker = false;
					}	
					tempImage =  "resources/map_marker_jrt.png";	
					break;	
				case "MMEM":
					if(!document.getElementById("MmemCheckBox").checked){
						createMarker = false;
					}	
					tempImage =  "resources/map_marker_mm4.png";	
					break;
																			
				default:
					createMarker = true;
					tempImage = 'http://maps.google.com/mapfiles/marker_yellow.png';
					break;
			}
			
			if (createMarker){
				whMarker = new google.maps.Marker({
			    	position: new google.maps.LatLng(whLocations[j]['lat'], whLocations[j]['lng']),
			    	animation: google.maps.Animation.DROP,
			    	icon: tempImage,
			    	//label: whLocations[j]['hdName'],
			    	zIndex: google.maps.Marker.MAX_ZINDEX + 1,
			    	map: map
			   	});
			   	//Adds a info window customized to each wholesaler
				google.maps.event.addListener(whMarker, 'click', (function(whMarker, j){
			    	return function() {
				   		var tempInfo = "";
				   		tempInfo += '<div style="border-radius: 5px; background-color: #336fa2; color: white; padding:15px;">';
				    	if(whLocations[j]['storename'] != ''){
				    		if(whLocations[j]['website'] != ''){
				    			tempInfo += '<strong><a style="background-color:#336fa2; color:white; text-decoration: underline;" href="'+ whLocations[j]['website'] +'">' + whLocations[j]['storename'] + '</a></strong><br>';
				    		}
				    		else {
				    			tempInfo += '<strong>' + whLocations[j]['storename'] + '</strong><br>';
				    		}
				    	}
				    	if(whLocations[j]['address'] != ''){
				    		tempInfo += '<strong>' + whLocations[j]['address'] + '</strong><br>';
				    	}
				    	if(whLocations[j]['phone'] != ''){
				    		tempInfo += '<strong>' + whLocations[j]['phone'] + '</strong><br>';
				    	}
				    	if(whLocations[j]['email'] != ''){
				    		tempInfo += '<strong>' + whLocations[j]['email'] + '</strong><br>';
				    	}
				    	if(whLocations[j]['manager'] != ''){
				    		tempInfo += '<strong>Manager: ' + whLocations[j]['manager'] + '</strong><br>';
				    	}				    
				    	tempInfo += '</div>';					    					    					    	
				    	infowindow.setContent(tempInfo);
			         	infowindow.open(map, whMarker);       	
					}
			  	})(whMarker, j));
				whMarkers.push(whMarker);
			}
		}        
	}    
	</script>
	<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>	
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCHUCK19x4cB0ZzPxMqbKcEFjy6vspjQn4&callback=reloadMap"></script>
