<?php
session_start();

// Set Content-Type header for JSON response
header("Content-Type: application/json");
file_put_contents('data_test_mani.txt', print_r("test1", true));
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Optional headers for CORS and security
    // header("Access-Control-Allow-Origin: *");
    // header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
    // header("X-Frame-Options: DENY");
    // header("X-XSS-Protection: 1; mode=block");
    // header("X-Content-Type-Options: nosniff");

    include('db.php');
file_put_contents('data_test2.txt', print_r("test2", true));
    // $db = new Database();
    // $db->connect();
    // $db->sql("SET NAMES 'utf8'");

    $response = array();

    // Retrieve and decode incoming JSON data
    $rawData = file_get_contents("php://input");
    $data1 = json_decode($rawData, true);

    // Define the secret key for the X-VERIFY header
    $secretKey = "c20aa13b-2d4e-47eb-b740-9155696ed973";

    // Verify data received from callback
    if (isset($data1['response'])) {
        // Decode base64-encoded data
        $base64EncodedData = $data1['response'];
        $decodedData = json_decode(base64_decode($base64EncodedData), true);

        // Generate the X-VERIFY header value
        $hash = hash('sha256', $base64EncodedData . $secretKey);
        $xVerifyHeader = $hash . '###1';

        // Add the X-VERIFY header to the response
        header("X-VERIFY: $xVerifyHeader");

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

        // Respond with a success message
        echo $res = json_encode(['status' => 'success', 'message' => 'Data received and processed']);
        file_put_contents('data_error.txt', print_r($res, true));
    } else {
        // Respond with an error if 'response' key is missing
        http_response_code(400);
        echo $res1 = json_encode(['status' => 'error', 'message' => 'Invalid data']);
        file_put_contents('data_error_catch.txt', print_r($res1, true));
    }
} else {
    // Respond with an error if the request method is not POST
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Only POST requests are allowed']);
}
?>
