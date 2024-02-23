<?php
session_start();

// set time for session timeout
$currentTime = time() + 25200;
$expired = 3600;

// if session not set go to login page
if (!isset($_SESSION['username'])) {
    header("location:index.php");
}

// if current time is more than session timeout back to login page
if ($currentTime > $_SESSION['timeout']) {
    session_destroy();
    header("location:index.php");
}

// destroy previous session timeout and create new one
unset($_SESSION['timeout']);
$_SESSION['timeout'] = $currentTime + $expired;

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Asia/Kolkata');

include_once('../includes/custom-functions.php');
$fn = new custom_functions;
include_once('../includes/crud.php');
include_once('../includes/variables.php');
$db = new Database();
$currentdate = date('Y-m-d');
$db->connect();

        // Get the current date and time
        $date = new DateTime('now');

        // Round off to the nearest hour
        $date->modify('+' . (60 - $date->format('i')) . ' minutes');
        $date->setTime($date->format('H'), 0, 0);
    
        // Format the date and time as a string
        $date_string = $date->format('Y-m-d H:i:s');
        $currentdate = date('Y-m-d');

        //users 
        if (isset($_GET['table']) && $_GET['table'] == 'users') {

            $offset = 0;
            $limit = 10;
            $where = '';
            $sort = 'id';
            $order = 'DESC';

            if (isset($_GET['referred_by']) && $_GET['referred_by'] != '') {
                $referred_by = $db->escapeString($fn->xss_clean($_GET['referred_by']));
                $where .= "AND referred_by = '$referred_by' "; // Properly append the condition
            }
            if (isset($_GET['offset']))
                $offset = $db->escapeString($_GET['offset']);
            if (isset($_GET['limit']))
                $limit = $db->escapeString($_GET['limit']);
            if (isset($_GET['sort']))
                $sort = $db->escapeString($_GET['sort']);
            if (isset($_GET['order']))
                $order = $db->escapeString($_GET['order']);
        
                if (isset($_GET['search']) && !empty($_GET['search'])) {
                    $search = $db->escapeString($_GET['search']);
                    $where .= " AND (id LIKE '%" . $search . "%' OR name LIKE '%" . $search . "%' OR mobile LIKE '%" . $search . "%' OR refer_code LIKE '%" . $search . "%')";
                }
            if (isset($_GET['sort'])){
                $sort = $db->escapeString($_GET['sort']);
            }
            if (isset($_GET['order'])){
                $order = $db->escapeString($_GET['order']);
            }
            $sql = "SELECT COUNT(`id`) as total FROM `users` ";
            $db->sql($sql);
            $res = $db->getResult();
            foreach ($res as $row)
                $total = $row['total'];
           
                $sql = "SELECT * FROM users WHERE 1=1 " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
                $db->sql($sql);
                $res = $db->getResult();
        
            $bulkData = array();
            $bulkData['total'] = $total;
            
            $rows = array();
            $tempRow = array();
        
            foreach ($res as $row) {
        
                
                $operate = ' <a href="edit-users.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
                $operate .= ' <a class="text text-danger" href="delete-users.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
                $tempRow['id'] = $row['id'];
                $tempRow['name'] = $row['name'];
                $tempRow['mobile'] = $row['mobile'];
                $tempRow['email'] = $row['email'];
                $tempRow['age'] = $row['age'];
                $tempRow['city'] = $row['city'];
                $tempRow['state'] = $row['state'];
                $tempRow['refer_code'] = $row['refer_code'];
                $tempRow['referred_by'] = $row['referred_by'];
                $tempRow['recharge'] = $row['recharge'];
                $tempRow['balance'] = $row['balance'];
                $tempRow['total_earnings'] = $row['total_earnings'];
                $tempRow['today_income'] = $row['today_income'];
                $tempRow['device_id'] = $row['device_id'];
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
            $bulkData['rows'] = $rows;
            print_r(json_encode($bulkData));
        }

        //plan
        if (isset($_GET['table']) && $_GET['table'] == 'plan') {

            $offset = 0;
            $limit = 10;
            $where = '';
            $sort = 'id';
            $order = 'DESC';
            if (isset($_GET['offset']))
                $offset = $db->escapeString($_GET['offset']);
            if (isset($_GET['limit']))
                $limit = $db->escapeString($_GET['limit']);
            if (isset($_GET['sort']))
                $sort = $db->escapeString($_GET['sort']);
            if (isset($_GET['order']))
                $order = $db->escapeString($_GET['order']);
        
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = $db->escapeString($_GET['search']);
                $where .= "WHERE id like '%" . $search . "%' OR products like '%" . $search . "%'";
            }
            if (isset($_GET['sort'])){
                $sort = $db->escapeString($_GET['sort']);
            }
            if (isset($_GET['order'])){
                $order = $db->escapeString($_GET['order']);
            }
            $sql = "SELECT COUNT(`id`) as total FROM `plan` ";
            $db->sql($sql);
            $res = $db->getResult();
            foreach ($res as $row)
                $total = $row['total'];
           
            $sql = "SELECT * FROM plan " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
            $db->sql($sql);
            $res = $db->getResult();
        
            $bulkData = array();
            $bulkData['total'] = $total;
            
            $rows = array();
            $tempRow = array();
        
            foreach ($res as $row) {
        
                
                $operate = ' <a href="edit-plan.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
                $operate .= ' <a class="text text-danger" href="delete-plan.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
                $tempRow['id'] = $row['id'];
                $tempRow['products'] = $row['products'];
                $tempRow['price'] = $row['price'];
                $tempRow['daily_income'] = $row['daily_income'];
                $tempRow['daily_quantity'] = $row['daily_quantity'];
                $tempRow['monthly_income'] = $row['monthly_income'];
                $tempRow['invite_bonus'] = $row['invite_bonus'];
                if (!empty($row['image'])) {
                    $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";
                } else {
                    $tempRow['image'] = 'No Image';
                }
                $tempRow['operate'] = $operate;
                $rows[] = $tempRow;
            }
            $bulkData['rows'] = $rows;
            print_r(json_encode($bulkData));
        }

        //withdrawals table goes here
if (isset($_GET['table']) && $_GET['table'] == 'withdrawals') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    
    if ((isset($_GET['status'])  && $_GET['status'] != '')) {
        $status = $db->escapeString($fn->xss_clean($_GET['status']));
        $where .= "AND w.status=$status ";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($fn->xss_clean($_GET['search']));
            $where .= "AND (u.mobile LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%'  OR w.datetime LIKE '%" . $search . "%') ";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);

    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);

    }        
    $join = "WHERE w.user_id = u.id ";

    $sql = "SELECT COUNT(u.id) as `total` FROM `withdrawals` w,`users` u $join " . $where . "";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];

    $sql = "SELECT w.id AS id,w.*,u.name,u.mobile,u.account_num,u.holder_name,u.bank,u.branch,u.ifsc,w.status AS status FROM `withdrawals` w,`users` u $join 
          $where ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;

    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        $checkbox = '<input type="checkbox" name="enable[]" value="'.$row['id'].'">';
        $amount = $row['amount'];
        $tempRow['column'] = $checkbox;
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['account_num'] = ','.$row['account_num'].',';
        $tempRow['holder_name'] = $row['holder_name'];
        $tempRow['bank'] = $row['bank'];
        $tempRow['branch'] = $row['branch'];
        $tempRow['ifsc'] = $row['ifsc'];
        $amount = $row['amount'];

        if ($amount < 250) {
            $taxRate = 0.05; // 5% tax rate
        } elseif ($amount <= 500) {
            $taxRate = 0.1; // 10% tax rate
        } elseif ($amount <= 1000) {
            $taxRate = 0.15; // 15% tax rate
        } else {
            $taxRate = 0.2; // 20% tax rate
        }
        
        $taxAmount = $amount * $taxRate;
        $pay_amount = $amount - $taxAmount;
        $tempRow['pay_amount'] = $pay_amount;
        $tempRow['amount'] = $row['amount'];
        $tempRow['datetime'] = $row['datetime'];
        if($row['status']==1)
                $tempRow['status']="<p class='text text-success'>Paid</p>";        
        elseif($row['status']==0)
                 $tempRow['status']="<p class='text text-primary'>Unpaid</p>"; 
        else
               $tempRow['status']="<p class='text text-danger'>Cancelled</p>";
        $rows[] = $tempRow;
        }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//transaction
if (isset($_GET['table']) && $_GET['table'] == 'transactions') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'date';
    $order = 'DESC';
    
    if ((isset($_GET['type']) && $_GET['type'] != '')) {
        $type = $db->escapeString($fn->xss_clean($_GET['type']));
        $where .= " AND l.type = '$type'";
    }
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));

       

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($fn->xss_clean($_GET['search']));
            $where .= "AND (u.mobile LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%') ";
        }
        
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
   
    $join = "LEFT JOIN `users` u ON l.user_id = u.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `transactions` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
     $sql = "SELECT l.id AS id,l.*,u.name,u.mobile  FROM `transactions` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
     $db->sql($sql);
     $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {
        $tempRow = array();
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['mobile'] = $row['mobile'];
        $tempRow['type'] = $row['type'];
        $tempRow['amount'] = $row['amount'];
        $tempRow['ads'] = $row['ads'];
        $tempRow['datetime'] = $row['datetime'];
        
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//user plan
//plan_details
if (isset($_GET['table']) && $_GET['table'] == 'user_plan') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= " AND (u.id LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%' OR p.products LIKE '%" . $search . "%')";
        }
        $join = "LEFT JOIN `users` u ON l.user_id = u.id LEFT JOIN `plan` p ON l.plan_id = p.id WHERE l.id IS NOT NULL " . $where;

        $sql = "SELECT COUNT(l.id) AS total FROM `user_plan` l " . $join;
        $db->sql($sql);
        $res = $db->getResult();
        foreach ($res as $row) {
            $total = $row['total'];
        }
        
        $sql = "SELECT l.id AS id, l.*, u.name AS user_name, u.mobile AS user_mobile, p.products
         AS plan_products FROM `user_plan` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
        $db->sql($sql);
        $res = $db->getResult();
        

    $bulkData = array();
    $bulkData['total'] = $total;
    $rows = array();
    $tempRow = array();
    foreach ($res as $row) {


        
        $operate = ' <a href="edit-user_plan.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-user_plan.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['user_name'] = $row['user_name'];
        $tempRow['user_mobile'] = $row['user_mobile'];
        $tempRow['plan_products'] = $row['plan_products'];
        $tempRow['days'] = $row['days'];
        $tempRow['price'] = $row['price'];
        $tempRow['daily_income'] = $row['daily_income'];
        $tempRow['monthly_income'] = $row['monthly_income'];
        $tempRow['validity'] = $row['validity'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//recharge
if (isset($_GET['table']) && $_GET['table'] == 'recharge') {
    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($fn->xss_clean($_GET['offset']));
    if (isset($_GET['limit']))
        $limit = $db->escapeString($fn->xss_clean($_GET['limit']));

    if (isset($_GET['sort']))
        $sort = $db->escapeString($fn->xss_clean($_GET['sort']));
    if (isset($_GET['order']))
        $order = $db->escapeString($fn->xss_clean($_GET['order']));
    if (isset($_GET['type']) && !empty($_GET['type'])){
        $type = $db->escapeString($fn->xss_clean($_GET['type']));
        $where .= "AND t.type = '$type' ";
      
    }
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($fn->xss_clean($_GET['search']));
        $where .= "AND s.name like '%" . $search . "%' OR t.amount like '%" . $search . "%' OR t.id like '%" . $search . "%'  OR t.type like '%" . $search . "%' OR s.mobile like '%" . $search . "%' ";
    }
    if (isset($_GET['sort'])) {
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])) {
        $order = $db->escapeString($_GET['order']);
    }
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $db->escapeString($_GET['search']);
        $where .= " AND (u.mobile LIKE '%" . $search . "%' OR u.name LIKE '%" . $search . "%' OR u.mobile LIKE '%" . $search . "%')";
    }
    $join = "LEFT JOIN `users` u ON l.user_id = u.id WHERE l.id IS NOT NULL " . $where;

    $sql = "SELECT COUNT(l.id) AS total FROM `recharge` l " . $join;
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row) {
        $total = $row['total'];
    }

    $sql = "SELECT l.id AS id, l.*, u.name FROM `recharge` l " . $join . " ORDER BY $sort $order LIMIT $offset, $limit";
    $db->sql($sql);
    $res = $db->getResult();

$bulkData = array();
$bulkData['total'] = $total;
$rows = array();
$tempRow = array();
foreach ($res as $row) {
        $operate = ' <a href="edit-recharge.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-recharge.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        if (!empty($row['image'])) {
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";
        } else {
            $tempRow['image'] = 'No Image';
        }
        $tempRow['recharge_amount'] = $row['recharge_amount'];
        if($row['status']==1)
        $tempRow['status'] ="<p class='text text-success'>Approved</p>";
    elseif($row['status']==0)
        $tempRow['status']="<p class='text text-danger'>Not-Approved</p>";
    else
        $tempRow['status']="<p class='text text-danger'>Cancelled</p>";
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}
//plan_slide
if (isset($_GET['table']) && $_GET['table'] == 'plan_slide') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= "WHERE id like '%" . $search . "%' OR name like '%" . $search . "%'";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `plan_slides` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM plan_slides " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-plan_slides.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-plan_slides.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['link'] = $row['link'];
        if(!empty($row['image'])){
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";

        }else{
            $tempRow['image'] = 'No Image';

        }
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//home_slide
if (isset($_GET['table']) && $_GET['table'] == 'home_slide') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= "WHERE id like '%" . $search . "%' OR name like '%" . $search . "%'";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `home_slides` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM home_slides " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-home_slides.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-home_slides.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['name'] = $row['name'];
        $tempRow['link'] = $row['link'];
        if(!empty($row['image'])){
            $tempRow['image'] = "<a data-lightbox='category' href='" . $row['image'] . "' data-caption='" . $row['image'] . "'><img src='" . $row['image'] . "' title='" . $row['image'] . "' height='50' /></a>";

        }else{
            $tempRow['image'] = 'No Image';

        }
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//explore
if (isset($_GET['table']) && $_GET['table'] == 'explore') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= "WHERE id like '%" . $search . "%' OR name like '%" . $search . "%'";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `explore` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM explore " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-explore.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-explore.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['main_content'] = $row['main_content'];
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

//Payment Setting
if (isset($_GET['table']) && $_GET['table'] == 'payment_setting') {

    $offset = 0;
    $limit = 10;
    $where = '';
    $sort = 'id';
    $order = 'DESC';
    if (isset($_GET['offset']))
        $offset = $db->escapeString($_GET['offset']);
    if (isset($_GET['limit']))
        $limit = $db->escapeString($_GET['limit']);
    if (isset($_GET['sort']))
        $sort = $db->escapeString($_GET['sort']);
    if (isset($_GET['order']))
        $order = $db->escapeString($_GET['order']);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $db->escapeString($_GET['search']);
            $where .= "WHERE id like '%" . $search . "%' OR name like '%" . $search . "%'";
        }
    if (isset($_GET['sort'])){
        $sort = $db->escapeString($_GET['sort']);
    }
    if (isset($_GET['order'])){
        $order = $db->escapeString($_GET['order']);
    }
    $sql = "SELECT COUNT(`id`) as total FROM `payment_setting` ";
    $db->sql($sql);
    $res = $db->getResult();
    foreach ($res as $row)
        $total = $row['total'];
   
    $sql = "SELECT * FROM payment_setting " . $where . " ORDER BY " . $sort . " " . $order . " LIMIT " . $offset . ", " . $limit;
    $db->sql($sql);
    $res = $db->getResult();

    $bulkData = array();
    $bulkData['total'] = $total;
    
    $rows = array();
    $tempRow = array();

    foreach ($res as $row) {

        
        $operate = ' <a href="edit-payment_setting.php?id=' . $row['id'] . '"><i class="fa fa-edit"></i>Edit</a>';
        $operate .= ' <a class="text text-danger" href="delete-payment_setting.php?id=' . $row['id'] . '"><i class="fa fa-trash"></i>Delete</a>';
        $tempRow['id'] = $row['id'];
        $tempRow['video_link'] = $row['video_link'];
        if(!empty($row['qr_image'])){
            $tempRow['qr_image'] = "<a data-lightbox='category' href='" . $row['qr_image'] . "' data-caption='" . $row['qr_image'] . "'><img src='" . $row['qr_image'] . "' title='" . $row['qr_image'] . "' height='50' /></a>";

        }else{
            $tempRow['qr_image'] = 'No Image';

        }
        $tempRow['operate'] = $operate;
        $rows[] = $tempRow;
    }
    $bulkData['rows'] = $rows;
    print_r(json_encode($bulkData));
}

$db->disconnect();

