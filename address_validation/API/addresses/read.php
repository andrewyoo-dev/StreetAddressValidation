<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/address.php';
 //echo "<script>console.log( "1" );</script>"
// instantiate database and product object
$database = new Database();
$db = $database->dbConnection();


 //echo "<script>console.log( "2" );</script>"

// initialize object
$address = new Address($db);
$stmt = $address->read();
$num = $stmt->rowCount();
 

 //echo "<script>console.log( "3" );</script>"
// check if more than 0 record found
if($num>0){
 
    // products array
    $address_arr=array();
    $address_arr["records"]=array();
 
    // retrieve our table contents
    // fetch() is faster than fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);
 
        $address_item=array(
            "id" => $id,
            "firstname" => $firstname,
            "lastname" => $lastname,
            "street" => $street,
            "city" => $city,
            "state" => $state,
            "zip" => $zip,
            "phone" => $phone
        );
 
        array_push($address_arr["records"], $address_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
    echo json_encode($address_arr);
}
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no products found
    echo json_encode(
        array("message" => "No products found.")
    );
}