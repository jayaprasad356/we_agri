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
if (empty($_POST['plan_id'])) {
    $response['success'] = false;
    $response['message'] = "Plan ID is Empty";
    print_r(json_encode($response));
    return false;
}

$plan_id = $db->escapeString($_POST['plan_id']);


$sql = "SELECT * FROM plan WHERE id = $plan_id";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1){

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
