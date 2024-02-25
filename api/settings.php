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
     
$sql = "SELECT * FROM settings";
$db->sql($sql);
$res= $db->getResult();
$num = $db->numRows($res);

if ($num >= 1){
    $rows = array();
    $temp = array();
    foreach ($res as $row) {
        $temp['id'] = $row['id'];
        $temp['whatsapp_group'] = $row['whatsapp_group'];
        $temp['telegram_channel'] = $row['telegram_channel'];
        $temp['max_withdrawal'] = $row['max_withdrawal'];
        $temp['min_withdrawal'] = $row['min_withdrawal'];

        $rows[] = $temp;
    }
    $response['success'] = true;
    $response['message'] = "Settings Listed Successfully";
    $response['data'] = $rows;
    print_r(json_encode($response));
}
else{
    $response['success'] = false;
    $response['message'] = "Data Not found";
    print_r(json_encode($response));

}
