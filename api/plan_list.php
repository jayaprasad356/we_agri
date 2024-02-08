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

$sql = "SELECT * FROM users WHERE id = $user_id ";
$db->sql($sql);
$user = $db->getResult();

if (empty($user)) {
    $response['success'] = false;
    $response['message'] = "User not found";
    print_r(json_encode($response));
    return false;
}


$sql = "SELECT * FROM plan";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);

if ($num >= 1){
    $rows = array();
    $temp = array();
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['crop'] = $row['crop'];
        $temp['price'] = $row['price'];
        $temp['image'] = DOMAIN_URL . $row['image'];
        $temp['daily_income'] = $row['daily_income'];
        $temp['total_income'] = $row['total_income'];
        $temp['invite_bonus'] = $row['invite_bonus'];
        $temp['validity'] = $row['validity'];
        $rows[] = $temp;
    }
    $response['success'] = true;
    $response['message'] = "Plan Details Retrieved Successfully";
    $response['data'] = $res;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Plan Not found";
    print_r(json_encode($response));

}
