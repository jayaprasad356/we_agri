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
include_once('../includes/custom-functions.php');
include_once('../includes/functions.php');
$fn = new functions;



if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    print_r(json_encode($response));
    return false;
}

if (empty($_POST['plan_id'])) {
    $response['success'] = false;
    $response['message'] = "Plan Id is Empty";
    print_r(json_encode($response));
    return false;
}

$user_id = $db->escapeString($_POST['user_id']);
$plan_id = $db->escapeString($_POST['plan_id']);

$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT * FROM plan WHERE id = $plan_id ";
$db->sql($sql);
$product = $db->getResult();

if (empty($product)) {
    $response['success'] = false;
    $response['message'] = "Plan not found";
    print_r(json_encode($response));
    return false;
}

$price = $product[0]['price'];
$daily_income = $product[0]['daily_income'];
$total_income = $product[0]['total_income'];
$validity = $product[0]['validity'];

$sql = "INSERT INTO user_plan (`user_id`,`plan_id`,`price`,`daily_income`,`total_income`,`validity`) VALUES ('$user_id','$plan_id','$price','$daily_income','$total_income','$validity')";
$db->sql($sql);
$res = $db->getResult();
$response['success'] = true;
$response['message'] = "User Plan Added Successfully";
print_r(json_encode($response));

?>