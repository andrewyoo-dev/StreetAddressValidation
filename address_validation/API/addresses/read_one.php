<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/address.php';
 
// get database connection
$database = new Database();
$db = $database->dbConnection();
 
// prepare product object
$address = new Address($db);
 
// set ID property of record to read
$address->id = isset($_GET['id']) ? $_GET['id'] : die();
 
// read the details of product to be edited
$address->readOne();
 
if($address->firstname!=null){
    // create array
    $address_arr = array(
        "id" => $address -> id,
        "firstname" => $address -> firstname,
        "lastname" => $address -> lastname,
        "street" => $address -> street,
        "city" => $address -> city,
        "state" => $address -> state,
        "zip" => $address -> zip,
        "phone" => $address -> phone
    );
 
    // set response code - 200 OK
    http_response_code(200);
 
    // make it json format
    echo json_encode($address_arr);
}
 
else{
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user product does not exist
    echo json_encode(array("message" => "Address does not exist."));
}
?>