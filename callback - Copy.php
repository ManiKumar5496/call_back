<?php
session_start();

// Corrected Content-Type header for JSON
header("Content-Type: application/json");

//Optional headers for CORS and security
//header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
// header("X-Frame-Options: DENY"); header("X-XSS-Protection: 1; mode=block");
// header("X-Content-Type-Options: nosniff");

include('db.php');

//$db = new Database();
//$db->connect();
//$db->sql("SET NAMES 'utf8'");

$response = array();

// Retrieve and decode incoming JSON data
$rawData = file_get_contents("php://input");
$data1 = json_decode($rawData, true);

// Verify data received from callback
//if (isset($data1['response'])) {
    // Decode base64-encoded data
    $base64EncodedData = $data1['response'];
    $decodedData = json_decode(base64_decode($base64EncodedData), true);

    // Extract required fields
    $data = array(
        'code' => $decodedData['code'] ?? null,
        'message' => $decodedData['message'] ?? null,
        'merchantId' => $decodedData['data']['merchantId'] ?? null,
        'merchantTransactionId' => $decodedData['data']['merchantTransactionId'] ?? null,
        'transactionId' => $decodedData['data']['transactionId'] ?? null,
        'amount' => $decodedData['data']['amount'] ?? null,
        'state' => $decodedData['data']['state'] ?? null,
        'payResponseCode' => $decodedData['data']['payResponseCode'] ?? null,
        'upiTransactionId' => $decodedData['data']['paymentInstrument']['upiTransactionId'] ?? null
    );

    // Save decoded data to a file (for debugging or logging purposes)
	file_put_contents('data_test.txt', print_r("test", true));
    file_put_contents('data.txt', print_r($decodedData, true));

    // Respond with a success message (if needed)
    echo $res = json_encode(['status' => 'success', 'message' => 'Data received and processed']);
	file_put_contents('data_error.txt', print_r($res, true));
//} else {
    // Respond with an error if 'response' key is missing
  //  http_response_code(400);
  //  echo $res1=json_encode(['status' => 'error', 'message' => 'Invalid data']);
	//file_put_contents('data_error_catch.txt', print_r($res1, true));
//}
?>
