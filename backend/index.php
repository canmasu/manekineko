<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');
  
// include JSON file to memory
$json = file_get_contents('../src/metadata/'.$_GET['token'].'.json');
  
if($json!=null){    

    // set response code - 200 OK
    http_response_code(200);
  
    // make it json format
    echo $json;
}
  
else{
    // set response code - 404 Not found
    http_response_code(404);
  
    // tell the user product does not exist
    echo json_encode(array("message" => "Product does not exist."));
}
?>