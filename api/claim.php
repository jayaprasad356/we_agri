<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');

include_once('../includes/crud.php');

$db = new Database();
$db->connect();
include_once('../includes/custom-functions.php');
include_once('../includes/functions.php');
$fn = new functions;

if (empty($_POST['user_id'])) {
    $response['success'] = false;
    $response['message'] = "User Id is Empty";
    echo json_encode($response);
    return;
}

if (empty($_POST['plan_id'])) {
    $response['success'] = false;
    $response['message'] = "Plan Id is Empty";
    echo json_encode($response);
    return;
}


$user_id = $db->escapeString($_POST['user_id']);
$plan_id = $db->escapeString($_POST['plan_id']);

$sql = "SELECT id,referred_by FROM users WHERE id = $user_id";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    echo json_encode($response);
    return;
}
$referred_by = $user[0]['referred_by'];
$sql = "SELECT * FROM user_plan WHERE user_id = $user_id AND plan_id = $plan_id";
$db->sql($sql);
$user_plan = $db->getResult();
if (empty($user_plan)) {
    $response['success'] = false;
    $response['message'] = "User Plan not found";
    echo json_encode($response);
    return;
}
$claim = $user_plan[0]['claim'];

if ($claim == 0) {
    $response['success'] = false;
    $response['message'] = "You already claimed this plan";
    print_r(json_encode($response));
    return false;
}

$sql = "SELECT daily_income FROM plan WHERE id = $plan_id";
$db->sql($sql);
$plan = $db->getResult();

if (empty($plan)) {
    $response['success'] = false;
    $response['message'] = "Plan not found";
    echo json_encode($response);
    return;
}
$daily_income = $plan[0]['daily_income'];
$ten_percent = $daily_income * 0.1;

$sql = "UPDATE user_plan SET claim = 0 WHERE plan_id = $plan_id AND user_id = $user_id";
$db->sql($sql);

$sql = "UPDATE users SET balance = balance + $daily_income, today_income = today_income + $daily_income, total_income = total_income + $daily_income WHERE id = $user_id";
$db->sql($sql);

$sql = "UPDATE users SET balance = balance + $ten_percent, today_income = today_income + $ten_percent, total_income = total_income + $ten_percent WHERE refer_code = '$referred_by'";
$db->sql($sql);



$response['success'] = true;
$response['message'] = "Claim Updated Successfully";

echo json_encode($response);
?>
