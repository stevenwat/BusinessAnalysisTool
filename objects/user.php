<?php
// 'user' object
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "users";
 
    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $contact_number;
    public $address;
    public $password;
    public $access_level;
    public $access_code;
    public $status;
    public $created;
    public $modified;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
    
    // check if given email exist in the database
    function emailExists(){
    
    	// query to check if email exists
    	$query = "SELECT id, firstname, lastname, access_level, password, status
            FROM " . $this->table_name . "
            WHERE email = ?
            LIMIT 0,1";
    	$stmt = $this->conn->prepare( $query );
    	$this->email=htmlspecialchars(strip_tags($this->email));
    	$stmt->bindParam(1, $this->email);
    	$stmt->execute();
    	$num = $stmt->rowCount();
    
    	// if email exists, assign values to object properties for easy access and use for php sessions
    	if($num>0){
    		$row = $stmt->fetch(PDO::FETCH_ASSOC);
     		$this->id = $row['id'];
    		$this->firstname = $row['firstname'];
    		$this->lastname = $row['lastname'];
    		$this->access_level = $row['access_level'];
    		$this->password = $row['password'];
    		$this->status = $row['status'];
    		return true;
    	}
    
    	// return false if email does not exist in the database
    	return false;
    }    
    
    // create new user record
    function create(){
    	$this->created=date('Y-m-d H:i:s');
    	$query = "INSERT INTO
                " . $this->table_name . "
            SET
                firstname = :firstname,
                lastname = :lastname,
                email = :email,
                contact_number = :contact_number,
                address = :address,
                password = :password,
                access_level = :access_level,
                status = :status,
                created = :created";
    	$stmt = $this->conn->prepare($query);
    
    	$this->firstname=htmlspecialchars(strip_tags($this->firstname));
    	$this->lastname=htmlspecialchars(strip_tags($this->lastname));
    	$this->email=htmlspecialchars(strip_tags($this->email));
    	$this->contact_number=htmlspecialchars(strip_tags($this->contact_number));
    	$this->address=htmlspecialchars(strip_tags($this->address));
    	$this->password=htmlspecialchars(strip_tags($this->password));
    	$this->access_level=htmlspecialchars(strip_tags($this->access_level));
    	$this->status=htmlspecialchars(strip_tags($this->status));
    
    	$stmt->bindParam(':firstname', $this->firstname);
    	$stmt->bindParam(':lastname', $this->lastname);
    	$stmt->bindParam(':email', $this->email);
    	$stmt->bindParam(':contact_number', $this->contact_number);
    	$stmt->bindParam(':address', $this->address);
    
    	// hash the password before saving to database
    	$password_hash = password_hash($this->password, PASSWORD_BCRYPT);
    	$stmt->bindParam(':password', $password_hash);
    
    	$stmt->bindParam(':access_level', $this->access_level);
    	$stmt->bindParam(':status', $this->status);
    	$stmt->bindParam(':created', $this->created);
    
    	// execute the query, also check if query was successful
    	if($stmt->execute()){
    		return true;
    	}else{
    		$this->showError($stmt);
    		return false;
    	}
    }   

    // read all user records
    function readAll($from_record_num, $records_per_page){
    	$query = "SELECT
                id,
                firstname,
                lastname,
                email,
                contact_number,
                access_level,
                created
            FROM " . $this->table_name . "
            ORDER BY id DESC
            LIMIT ?, ?";
   
    	$stmt = $this->conn->prepare( $query );
    	$stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
    	$stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
    	$stmt->execute();
    	return $stmt;
    }   

    // used for paging users
    public function countAll(){
    	// query to select all user records
    	$query = "SELECT id FROM " . $this->table_name . "";
    	$stmt = $this->conn->prepare($query);
    	$stmt->execute();
    	$num = $stmt->rowCount();
    	return $num;
    }    
}