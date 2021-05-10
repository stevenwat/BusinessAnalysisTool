<?php
// 'user' object
class WholesalerLocations{
 
    private $conn;
    private $wholesaler_info = "wholesalerinfo";
    
    //Wholesaler properties
    public $wholesaler_branch;    
    public $wholesaler_name;
    public $wholesaler_address;
    public $wholesaler_contact;
    public $wholesaler_latitude;
    public $wholesaler_longitude;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // grab all of the locations using the first provided email
    function grabCurrentWithoutLimit(){
    	// query to select all user records
    	 
    	$query = "SELECT wholesaler_name,
    			branch_abbrev,
    			store_name,
    			phone,
    			email,
    			address,
    			website,
    			manager,
    			latitude,
    			longitude FROM "
    			. $this->wholesaler_info .
        		" where latitude <> '';";
    	 
    	// prepare query statement
    	try {
			$stmt = $this->conn->prepare($query);
			$stmt->execute();
	    	//echo $stmt;
		} catch (Exception $e) {
			echo 'Caught exception: ', $e->getMessage(), "\n"; 
		}
    	// return values
    	return $stmt;
    }    
}