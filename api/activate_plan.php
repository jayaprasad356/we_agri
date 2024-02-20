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
$plan = $db->getResult();

if (empty($plan)) {
    $response['success'] = false;
    $response['message'] = "Plan not found";
    print_r(json_encode($response));
    return false;
}

$price = $plan[0]['price'];
$daily_income = $plan[0]['daily_income'];
$total_income = $plan[0]['total_income'];
$validity = $plan[0]['validity'];
$balance = $user[0]['balance'];
$recharge_balance = $user[0]['recharge_balance'];

$datetime = date('Y-m-d H:i:s');

$sql_check = "SELECT * FROM user_plan WHERE user_id = $user_id AND plan_id = $plan_id";
$db->sql($sql_check);
$res_check_user = $db->getResult();

if (!empty($res_check_user)) {
    $response['success'] = false;
    $response['message'] = "You have already applied this Plan";
    print_r(json_encode($response));
    return false;
}
if ($recharge_balance >= $price) {
    $sql = "UPDATE users SET recharge_balance = recharge_balance - $price WHERE id = $user_id";
    $db->sql($sql);


    $sql_insert_user_plan = "INSERT INTO user_plan (`user_id`,`plan_id`,`price`,`daily_income`,`total_income`,`validity`) VALUES ('$user_id','$plan_id','$price','$daily_income','$total_income','$validity')";
    $db->sql($sql_insert_user_plan);

     $sql_insert_transaction = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$user_id', '$price', '$datetime', 'purchase_plan')";
     $db->sql($sql_insert_transaction);

    $response['success'] = true;
    $response['message'] = "Apply User Plan successfully";
 }else {
    $response['success'] = false;
    $response['message'] = "Insufficient balance to apply for this plan";
}

print_r(json_encode($response));
?>