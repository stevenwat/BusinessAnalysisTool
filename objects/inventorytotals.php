<?php
// 'user' object
class InventoryTotals{
 
    private $conn;
    private $tbl_inventory_totals = "tblinventorytotals";
    
    //Prospect properties 
    public $inventory_region;
    public $inventory_branch;
    public $inventory_totalcost;
    
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    function grabInventoryTotals(){
    	$query = "SELECT Region,
    			Branch,
    			TotalCost
    			FROM "
    			. $this->tbl_inventory_totals .';';
    	try {
	    	$stmt = $this->conn->prepare($query);
	    	$stmt->execute();
		} catch (Exception $e) {
			echo 'Caught exception: ', $e->getMessage(), "\n"; 
		}
    	// return values
    	return $stmt;
    }    
}
?>