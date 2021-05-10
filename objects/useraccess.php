<?php
// 'user' object
class UserAccess{

	private $conn;
	private $tbl_user_access = "tblbihash";

	// constructor
	public function __construct($db){
		$this->conn = $db;
	}

	function grabAccess(){
		$query = "SELECT 
			Username, 
			hash, 
			locationServicesAccess, 
			salesOverviewAccess, 
			debtorAnalysisAccess, 
			inventoryValAccess 
			FROM ". $this->tbl_user_access .';';
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