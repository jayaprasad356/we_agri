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

$invite_bonus = $plan[0]['invite_bonus'];
$price = $plan[0]['price'];
$daily_income = $plan[0]['daily_income'];
$monthly_income = $plan[0]['monthly_income'];
$daily_quantity = $plan[0]['daily_quantity'];
$balance = $user[0]['balance'];
$recharge = $user[0]['recharge'];
$total_assets = $user[0]['total_assets'];
$refer_code = $user[0]['refer_code'];
$referred_by = $user[0]['referred_by'];

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
if ($recharge >= $price) {

    $sql = "UPDATE users SET recharge = recharge - $price, total_assets = total_assets + $price WHERE id = $user_id";
    $db->sql($sql);

    if($refer_code){
        $sql = "SELECT * FROM users WHERE refer_code = '$referred_by'";
        $db->sql($sql);
        $res = $db->getResult();
        $num = $db->numRows($res);

        if ($num == 1) {
            $r_id = $res[0]['id'];
            $sql = "UPDATE `users` SET `balance` = `balance` + $invite_bonus WHERE `refer_code` = '$referred_by'";
            $db->sql($sql);

            $sql = "INSERT INTO transactions (`user_id`, `amount`, `datetime`, `type`) VALUES ('$r_id', '$invite_bonus', '$datetime', 'invite_bonus')";
            $db->sql($sql);
        }

    }
    
    $sql_insert_user_plan = "INSERT INTO user_plan (`user_id`,`plan_id`,`price`,`daily_income`,`monthly_income`,`daily_quantity`) VALUES ('$user_id','$plan_id','$price','$daily_income','$monthly_income','$daily_quantity')";
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