<?php
	$pass = false; 
	if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
	    //$Username = $_SERVER['PHP_AUTH_USER'];
	    //$Password = $_SERVER['PHP_AUTH_PW'];
	    //echo password_hash($Password, PASSWORD_DEFAULT);
	    /*$dbName = "\\\\dc01\\data\\marketing\\margin analysis\\NewAdjustedSalesMargin.mdb";
	    if (!file_exists($dbName)) {
	    	die("Could not connect to the verification server.");
	    }
	    $db_username = "stevenwat";
	    $db_password = "kzw67jbtnh72tmm7";
	    $database = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb, *.accdb)}; DBQ=$dbName; Uid=$db_username; Pwd=$db_password;");
	    $sql  = "SELECT username, hash, locationServicesAccess, salesOverviewAccess, debtorAnalysisAccess, inventoryValAccess FROM tblBiHash";
	    $handle = $database->prepare($sql);
	    $handle->execute();
	    $result = $handle->fetchAll(\PDO::FETCH_OBJ);	
	    foreach($result as $row){
	    	if(!empty($row->username)){
	    		if($row->username == $_SERVER['PHP_AUTH_USER']){
	    			if(!empty($row->hash)){
	    				if(password_verify($_SERVER['PHP_AUTH_PW'],$row->hash)){
	    					/*Grab the user's access from the table*-/
	    					$access['location_access'] = $row->locationServicesAccess;
	    					$access['sales_access'] = $row->salesOverviewAccess;
	    					$access['debtor_access'] = $row->debtorAnalysisAccess;
	    					$access['inventory_access'] = $row->inventoryValAccess;
	    					$pass = true;
	    					break;
	    				}
	    			}	    			
	    		}
	    	}
	    }    */
		$Username = $_SERVER['PHP_AUTH_USER'];
		$Password = $_SERVER['PHP_AUTH_PW'];
		
		$database = new Database();
		$db = $database->getConnection();
		
		$user_access = new UserAccess($db);
		$stmt_access = $user_access->grabAccess();

		while ($row = $stmt_access->fetch(PDO::FETCH_ASSOC)){
			if(!empty($row['Username'])){
				if($row['Username'] == $_SERVER['PHP_AUTH_USER']){
					if(!empty($row['hash'])){
						if(password_verify($_SERVER['PHP_AUTH_PW'],$row['hash'])){
							/*Grab the user's access from the table*/
							$access['location_access'] = $row['locationServicesAccess'];
							$access['sales_access'] = $row['salesOverviewAccess'];
							$access['debtor_access'] = $row['debtorAnalysisAccess'];
							$access['inventory_access'] = $row['inventoryValAccess'];
							$pass = true;
							break;
						}
					}
				}
			}
		}
				
	    /*Set the default active tab for the navigation page based on the user's current access, location access needs to be prioritised if it is available*/
		$active_tab = '';
		if ($access['location_access']){
			$active_tab = 'location_access';
		} else {
			foreach($access as $x =>$x_value) {
				if ($x_value){
					$active_tab = $x;
					break;
				}
			}			
		}
	}
	if (!$pass){
		header('WWW-Authenticate: Basic realm="Secret page"');
		header('HTTP/1.0 401 Unauthorized');
		print "Login failed!\n";
		exit;
	}
	
	function getActiveTab(){

		return $temp_active_tab;
	}
?>