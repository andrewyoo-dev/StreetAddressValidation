<?php
class Address{

    // database connection and table name
    private $conn;
    private $table_name = "addresses";
    /*
    private $reg_name = ;
    private $reg_street = ;
    private $reg_city = ;
    private $reg_state = ;
    private $reg_zip = ;
    private $reg_phone = ;
    */
    // object properties
    public $id;
    public $firstname;
    public $lastname;
    public $street;
    public $city;
    public $state;
    public $zip;
    public $phone;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function valid()
    {
        $isVal = 0;
        if(!preg_match("/^[A-Z]{0,50}$/", $this->firstname))
            $isVal++;
        if(!preg_match("/^[A-Z]{0,50}$/", $this->lastname))
            $isVal++;
        if(!preg_match("/^[A-Za-z0-9 .-]{0,50}$/", $this->street))
            $isVal++;
        if(!preg_match("/^[A-Z]{0,25}$/", $this->city))
            $isVal++;
        if(!preg_match("/^(A[AELPKRSZ]|C[AOT]|D[CE]|F[LM]|G[AU]|HI|I[ADLN]|K[SY]|LA|M[ADEHINOPST]|N[CDEHJMVY]|O[HKR]|P[ARW]|RI|S[CD]|T[NX]|UT|V[AIT]|W[AIVY])$/", $this->state))
            $isVal++;
        if(!preg_match("/^([0-9]{5}(-[0-9]{4})?)$/", $this->zip))
            $isVal++;
        if(!preg_match("/^\([0-9]{3}\) [0-9]{3}-[0-9]{4}$/", $this->phone))
            $isVal++;
        return $isVal;
    }

    function empty()
    {
        if(!isset($this->firstname) && !isset($this->lastname) && !isset($this->street) && 
            !isset($this->city) && !isset($this->state) && !isset($this->zip) && !isset($this->phone))
            return 1;
        return 0;
    }

    // read products
    function read(){
 
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " p ORDER BY id";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        
        return $stmt;
    }

    function create(){
        $a = $this->valid();
        $b = $this->empty();
        if($a == 0 && $b == 0)
        {
            // query to insert record
            $query = 
                "INSERT INTO " . $this->table_name . "
                SET
                    firstname=:firstname, 
                    lastname=:lastname,
                    street=:street,
                    city=:city,
                    state=:state,
                    zip=:zip,
                    phone=:phone";

            // prepare query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->firstname=htmlspecialchars(strip_tags($this->firstname));
            $this->lastname=htmlspecialchars(strip_tags($this->lastname));
            $this->street=htmlspecialchars(strip_tags($this->street));
            $this->city=htmlspecialchars(strip_tags($this->city));
            $this->state=htmlspecialchars(strip_tags($this->state));
            $this->zip=htmlspecialchars(strip_tags($this->zip));
            $this->phone=htmlspecialchars(strip_tags($this->phone));
        
            // bind values
            $stmt->bindParam(":firstname", $this->firstname);
            $stmt->bindParam(":lastname", $this->lastname);
            $stmt->bindParam(":street", $this->street);
            $stmt->bindParam(":city", $this->city);
            $stmt->bindParam(":state", $this->state);
            $stmt->bindParam(":zip", $this->zip);
            $stmt->bindParam(":phone", $this->phone);
        
            // execute query
            if($stmt->execute()){
                return true;
            }
        }   
        return false;
    }


    function readOne(){
        // query to read single record
        $query = 
            "SELECT * FROM " . $this->table_name . " WHERE id = ?";
    
        // prepare query statement
        $stmt = $this->conn->prepare( $query );
    
        // bind id of product to be updated
        $stmt->bindParam(1, $this->id);
    
        // execute query
        $stmt->execute();
    
        // get retrieved row
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // set values to object properties
        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->street = $row['street'];
        $this->city = $row['city'];
        $this->state = $row['state'];
        $this->zip = $row['zip'];
        $this->phone = $row['phone'];
    }

    // update the product
    function update(){
        $num = $this->valid();
        if($num == 0)
        {
            // update query
            $query = 
                "UPDATE " . $this->table_name . "
                SET
                    firstname = :firstname, 
                    lastname = :lastname,
                    street = :street,
                    city = :city,
                    state = :state,
                    zip = :zip,
                    phone = :phone
                WHERE
                    id = :id";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->firstname=htmlspecialchars(strip_tags($this->firstname));
            $this->lastname=htmlspecialchars(strip_tags($this->lastname));
            $this->street=htmlspecialchars(strip_tags($this->street));
            $this->city=htmlspecialchars(strip_tags($this->city));
            $this->state=htmlspecialchars(strip_tags($this->state));
            $this->zip=htmlspecialchars(strip_tags($this->zip));
            $this->phone=htmlspecialchars(strip_tags($this->phone));
            $this->id=htmlspecialchars(strip_tags($this->id));

            // bind new values
            $stmt->bindParam(":firstname", $this->firstname);
            $stmt->bindParam(":lastname", $this->lastname);
            $stmt->bindParam(":street", $this->street);
            $stmt->bindParam(":city", $this->city);
            $stmt->bindParam(":state", $this->state);
            $stmt->bindParam(":zip", $this->zip);
            $stmt->bindParam(":phone", $this->phone);
            $stmt->bindParam(':id', $this->id);

            // execute the query
            if($stmt->execute()){
                return true;
            }
        }
        return false;
    }

    // delete the product
    function delete(){
    
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->id=htmlspecialchars(strip_tags($this->id));
    
        // bind id of record to delete
        $stmt->bindParam(1, $this->id);
    
        // execute query
        if($stmt->execute()){
            return true;
        }
    
        return false;
     
    }
}