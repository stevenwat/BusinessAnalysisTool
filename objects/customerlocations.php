<?php
// 'user' object
class CustomerLocations{
 
    private $conn;
    private $customer_info = "customerinfo";
    
    //Hd Locations properties
    public $cust_id;
    public $cust_deb_acc;    
    public $cust_name;
    public $cust_postcode;
    public $cust_address_used;
    public $cust_city;
    public $cust_contact;
    public $cust_terr_code;
    public $cust_latitude;
    public $cust_longitude;   
    public $cust_mobile; 
    public $cust_state;
    public $cust_suburb;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // grab all of the locations using the first provided email
    function grabCurrentWithoutLimit(){
    	// query to select all user records
    	 
    	$query = "SELECT deb_acc,
    			name,
    			latitude,
    			longitude FROM "
    			. $this->customer_info .
        		" where latitude <> '';";
        		//" and postcode = '2213';";
    			//WHERE Postcode = ?;";
    	
    	 
    	// prepare query statement
    	try {
    	$stmt = $this->conn->prepare($query);
    	 
    	// sanitize
    	//$this->hd_postcode=htmlspecialchars(strip_tags($this->hd_postcode));
    	 
    	// bind given email value
    	//$stmt->bindParam(1, $this->hd_postcode);
		
	    	// execute query
	    	$stmt->execute();
	    	//echo $stmt;
		} catch (Exception $e) {
			echo 'Caught exception: ', $e->getMessage(), "\n"; 
		}
    	// return values
    	return $stmt;
    }    
}