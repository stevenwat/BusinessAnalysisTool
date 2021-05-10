<?php
// 'user' object
class ProspectLocations{
 
    private $conn;
    private $prospect_info = "prospectinfo";
    
    //Prospect properties 
    public $prospect_name;
    public $prospect_address;
    public $prospect_granularity;
    public $prospect_latitude;
    public $prospect_longitude;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // grab all of the locations using the first provided email
    function grabCurrentWithoutLimit(){
    	// query to select all user records
    	 
    	$query = "SELECT name,
    			email,
    			address,
    			latitude,
    			longitude
    			FROM "
    			. $this->prospect_info .
        		" where latitude <> '' and 
        		granularity='PROPERTY';";
        		//and 
        		//state in('SA', 'NT', 'WA', 'TAS');";
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