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


$sql = "SELECT * FROM recharge";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);

if ($num >= 1){
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['user_id'] = $row['user_id'];
        $temp['image'] = DOMAIN_URL . $row['image'];
        $temp['recharge_amount'] = $row['recharge_amount'];
        $temp['status'] = $row['status'];
        $temp['datetime'] = $row['datetime'];
        $rows[] = $temp;
    }
    $response['success'] = true;
    $response['message'] = "Recharge Histoy Listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Slide Not found";
    print_r(json_encode($response));

}
