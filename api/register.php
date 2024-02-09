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

if (empty($_POST['name'])) {
    $response['success'] = false;
    $response['message'] = "Name is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['mobile'])) {
    $response['success'] = false;
    $response['message'] = "Mobilenumber is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['device_id'])) {
    $response['success'] = false;
    $response['message'] = "Device Id is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['age'])) {
    $response['success'] = false;
    $response['message'] = "Age is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['city'])) {
    $response['success'] = false;
    $response['message'] = "City is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['email'])) {
    $response['success'] = false;
    $response['message'] = "Email is Empty";
    print_r(json_encode($response));
    return false;
}
if (empty($_POST['state'])) {
    $response['success'] = false;
    $response['message'] = "State is Empty";
    print_r(json_encode($response));
    return false;
}


$name = $db->escapeString($_POST['name']);
$mobile = $db->escapeString($_POST['mobile']);
$referred_by = (isset($_POST['referred_by']) && !empty($_POST['referred_by'])) ? $db->escapeString($_POST['referred_by']) : "";
$device_id = $db->escapeString($_POST['device_id']);
$age = $db->escapeString($_POST['age']);
$city = $db->escapeString($_POST['city']);
$email = $db->escapeString($_POST['email']);
$state = $db->escapeString($_POST['state']);

$sql = "SELECT * FROM users WHERE mobile='$mobile'";
$db->sql($sql);
$res = $db->getResult();
$num = $db->numRows($res);
if ($num >= 1) {
    $response['success'] = false;
    $response['message'] ="Mobile Number Already Exists";
    print_r(json_encode($response));
    return false;
} else {

    if (!empty($referred_by)) {
        $sql = "SELECT id FROM users WHERE refer_code = '$referred_by'";
        $db->sql($sql);
        $refres = $db->getResult();
        $num = $db->numRows($refres);
        if ($num == 0) {
            $response['success'] = false;
            $response['message'] = "Invalid Refer Code";
            print_r(json_encode($response));
            return false;
        }
    }
    $sql = "INSERT INTO users (`mobile`,`name`,`referred_by`,`account_num`,`holder_name`,`bank`,`branch`,`ifsc`,`device_id`,`age`,`city`,`email`,`state`) VALUES ('$mobile','$name','$referred_by','','','','','','$device_id','$age','$city','$email','$state')";
    $db->sql($sql);
    $sql = "SELECT * FROM users WHERE mobile = '$mobile'";
    $db->sql($sql);
    $res = $db->getResult();
    $user_id = $res[0]['id'];
    

        if(empty($referred_by)){
            $refer_code = MAIN_REFER . $user_id;
    
        }
        else{
            if (strlen($referred_by) < 3) {
                $refer_code = MAIN_REFER . $user_id;
    
            }
            else{
                $refershot = substr($referred_by, 0, 3);
                $sql = "SELECT refer_code FROM admin WHERE refer_code = '$refershot'";
                $db->sql($sql);
                $ares = $db->getResult();
                $num = $db->numRows($ares);
                if ($num >= 1) {
                    $refer_code_db = $ares[0]['refer_code'];
                    $refer_code = $refer_code_db . $user_id;
    
                }else{
                    $refer_code = MAIN_REFER . $user_id;
    
                }
    

    
                
            }
       
        }
    
        $sql_query = "UPDATE users SET refer_code='$refer_code' WHERE id =  $user_id";
        $db->sql($sql_query);

    $sql = "SELECT * FROM users WHERE mobile = '$mobile'";
    $db->sql($sql);
    $res = $db->getResult();

    $response['success'] = true;
    $response['message'] = "Successfully Registered";
    $response['data'] = $res;
    print_r(json_encode($response));
}
?>
