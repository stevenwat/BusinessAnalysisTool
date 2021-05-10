<?php
// used to get mysql database connection
class OdbcDatabase{
    //private $db_name = "php_login_system";
    private $db_name = "\\\\dc01\\data\\marketing\\margin analysis\\NewAdjustedSalesMargin.mdb";
    private $username = "stevenwat";
    private $password = "kzw67jbtnh72tmm7";
    public $odbcconn;
 
    // get the database connection
    public function getConnection(){
        $this->$odbcconn = null;
        try{
            $this->$odbcconn = new PDO("odbc:DRIVER={Microsoft Access Driver (*.mdb, *.accdb)}; DBQ=$this->db_name; Uid=$this->username; Pwd=$this->password;");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->$odbcconn;
    }
}
?>