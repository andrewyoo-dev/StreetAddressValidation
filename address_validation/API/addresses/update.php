<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/address.php';
 
// get database connection
$database = new Database();
$db = $database->dbConnection();
 
// prepare product object
$address = new Address($db);
// get id of product to be edited
$data = json_decode(file_get_contents("php://input"));
//var_dump($data);
// set ID property of product to be edited
$address->id = $data->id;
 
// set product property values
$address->firstname = $data->firstname;
$address->lastname = $data->lastname;
$address->street = $data->street;
$address->city = $data->city;
$address->state = $data->state;
$address->zip = $data->zip;
$address->phone = $data->phone;
 
//var_dump($address);
// update the product
if($address->update()){
 
    // set response code - 200 ok
    http_response_code(200);
 
    // tell the user
    echo json_encode(array("message" => "Address was updated."));
}
 
// if unable to update the product, tell the user
else{
 
    // set response code - 503 service unavailable
    http_response_code(503);
 
    // tell the user
    echo json_encode(array("message" => "Unable to update address."));
}
?>