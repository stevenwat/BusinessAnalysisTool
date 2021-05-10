<?php
// 'user' object
class HdLocations{
 
    private $conn;
    private $hd_locations = "hdlocations";
    private $postcode_optimiser = "postcodeoptimiser";
    
    //Hd Locations properties
    public $hd_locations_id;
    public $hd_name;
    public $hd_email;
    public $hd_state;
    public $hd_suburb;
    public $hd_address;
    public $hd_phone;
    public $hd_postcode;
    public $hd_granularity;
    public $hd_latitude;
    public $hd_longitude;
    public $hd_website_url;
    public $hd_business_type;
     
    //Postcode Optimiser properties
    
 	public $op_postcode_to_compare;
 	public $op_lat_to_compare;
 	public $op_lon_to_compare;
 	public $op_postcode;
 	public $op_lat;
 	public $op_lon;
 	public $op_distance;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
    
    // grab all of the locations using the first provided email
    function grabCurrent($from_record_num, $records_per_page){
    	// query to select all user records
    	
    	$query = "SELECT Id, 
    			HdName, 
    			Email, 
    			State, 
    			Suburb, 
    			Address, 
    			Phone, 
    			Postcode, 
    			Granularity, 
    			Latitude, 
    			Longitude, 
    			WebsiteUrl, 
    			BusinessType FROM " . 
    			$this->hd_locations ."
    			WHERE Postcode = ? 
    			LIMIT ?,?;";
    	$stmt = $this->conn->prepare($query);
    	$this->hd_postcode=htmlspecialchars(strip_tags($this->hd_postcode));
    	$stmt->bindParam(1, $this->hd_postcode);
    	$stmt->bindParam(2, $from_record_num, PDO::PARAM_INT);
    	$stmt->bindParam(3, $records_per_page, PDO::PARAM_INT);    	
    	$stmt->execute();
    	return $stmt;
    } 

    // grab all of the locations using the first provided email
    function grabCurrentWithoutLimit(){
    	// query to select all user records
    	$query = "SELECT Id,
    			HdName,
    			Email,
    			State,
    			Suburb,
    			Address,
    			Phone,
    			Postcode,
    			Granularity,
    			Latitude,
    			Longitude,
    			WebsiteUrl,
    			BusinessType FROM " .
        			$this->hd_locations ."
    			WHERE Postcode = ?;";
   
    	$stmt = $this->conn->prepare($query);
    	$this->hd_postcode=htmlspecialchars(strip_tags($this->hd_postcode));
    	$stmt->bindParam(1, $this->hd_postcode);
    	$stmt->execute();
    	return $stmt;
    }    
    
    function grabPostCodeLatitudeAndLongitude(){
    	$query = "SELECT LatToCompare,
    			LonToCompare FROM " .
    	    			$this->postcode_optimiser ."
    			WHERE PostcodeToCompare = ?
    			LIMIT 2;";    	
    	$stmt = $this->conn->prepare($query);
    	$this->op_postcode_to_compare=htmlspecialchars(strip_tags($this->op_postcode_to_compare));  
    	$stmt->bindParam(1, $this->op_postcode_to_compare); 
    	$stmt->execute();    
    	$lat = $stmt->fetchColumn();
    	$lng = $stmt->fetchColumn(1);	
    	
    	return $lat . '+'. $lng;
    }
    
    function countAll(){
    	// query to select all user records
    	$query = "SELECT id FROM " . $this->hd_locations . "
    			WHERE Postcode = ?;";
    	
    	// prepare query statement
    	$stmt = $this->conn->prepare($query);
    	$this->hd_postcode=htmlspecialchars(strip_tags($this->hd_postcode));
    	$stmt->bindParam(1, $this->hd_postcode);
    	$stmt->execute();
    	
    	// get number of rows
    	$num = $stmt->rowCount();
    	
    	// return row count
    	return $num;    	
    }
}