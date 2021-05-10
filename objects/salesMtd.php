<?php
// 'user' object
class SalesMtd{
 
    private $conn;
    private $tbl_sales_mtd = "tblsalesmtd";
    
    //Prospect properties 
    public $salesmtd_region;
    public $salesmtd_branch;
    public $salesmtd_totalsales;
    public $salesmtd_totaladjmgn;
    
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // grab all of the locations using the first provided email
    function grabRecentSales(){
    	// query to select all user records
    	 
    	$query = "SELECT Region,
    			Branch,
    			TotalSales,
    			TotalAdjMgn
    			FROM "
    			. $this->tbl_sales_mtd .';';

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
?>