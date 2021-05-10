<?php
// 'user' object
class HdLocationsRest{
 
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
 	
 	public function __construct(){
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
}