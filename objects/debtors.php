<?php
// 'user' object
class Debtors{
 
    private $conn;
    private $tbl_debtors = "tblbidebtors";
    
    //Prospect properties 
    /*public $salesmtd_region;
    public $salesmtd_branch;
    public $salesmtd_totalsales;
    public $salesmtd_totaladjmgn;*/
    
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // grab all of the locations using the first provided email
    function grabDebtors(){
    	// query to select all user records
    	 
    	$query = "SELECT Branch, 
    			DebAcc, 
    			Name, 
    			OpenBalance, 
    			StopCredit, 
    			CreditLimit, 
    			CurrentBalance, 
    			Thirty, 
    			Sixty, 
    			Ninety, 
    			OneTwenty, 
    			MTDSales, 
    			MTDAdjMgn 
    			FROM ". $this->tbl_debtors .';';

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
?>