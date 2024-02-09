<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once('../includes/crud.php');

$db = new Database();
$db->connect();
if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User ID is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);

$sql_user = "SELECT * FROM users WHERE id = $user_id";
$db->sql($sql_user);
$res_user = $db->getResult();
$num = $db->numRows($res_user);

if ($num >= 1) {

    $start_date = '2024-02-02'; 
    $end_date = '2024-02-09';   

    $sql_transaction = "SELECT SUM(amount) AS total_amount FROM transactions WHERE user_id = $user_id AND datetime >= '$start_date' AND datetime <= '$end_date'";
    $db->sql($sql_transaction);
    $res_transaction = $db->getResult();
    $total_amount = $res_transaction[0]['total_amount'];

    $res_user[0]['7_days_earn'] = $total_amount;

    $response['success'] = true;
    $response['message'] = "User Details Retrieved Successfully";
    $response['data'] = $res_user;
    print_r(json_encode($response));
} else {
    $response['success'] = false;
    $response['message'] = "User Not found";
    print_r(json_encode($response));
}
?>
