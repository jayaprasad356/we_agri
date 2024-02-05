<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php

if (isset($_GET['id'])) {
	$ID = $db->escapeString($_GET['id']);
} else {
	// $ID = "";
	return false;
	exit(0);
}
if (isset($_POST['btnEdit'])) {

    $crop = $db->escapeString(($_POST['crop']));
    $price = $db->escapeString(($_POST['price']));
    $daily_income = $db->escapeString(($_POST['daily_income']));
    $total_income = $db->escapeString(($_POST['total_income']));
    $invite_bonus = $db->escapeString(($_POST['invite_bonus']));
    $validity = $db->escapeString(($_POST['validity']));
	$error = array();

    if (!empty($crop) && !empty($price) && !empty($daily_income) && !empty($total_income) && !empty($invite_bonus) && !empty($validity)) 
    {
		$sql_query = "UPDATE plan SET crop='$crop',price='$price',daily_income='$daily_income',total_income='$total_income',invite_bonus='$invite_bonus',validity='$validity' WHERE id =  $ID";
		$db->sql($sql_query);
		$update_result = $db->getResult();
		if (!empty($update_result)) {
			$update_result = 0;
		} else {
			$update_result = 1;
		}

		// check update result
		if ($update_result == 1) {
			$error['update_languages'] = " <section class='content-header'><span class='label label-success'>Plan updated Successfully</span></section>";
		} else {
			$error['update_languages'] = " <span class='label label-danger'>Failed to Update</span>";
		}
	}
}


// create array variable to store previous data
$data = array();

$sql_query = "SELECT * FROM plan WHERE id =" . $ID;
$db->sql($sql_query);
$res = $db->getResult();

if (isset($_POST['btnCancel'])) { ?>
	<script>
		window.location.href = "plan.php";
	</script>
<?php } ?>
<section class="content-header">
	<h1>
		Edit Plan<small><a href='plan.php'><i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Plan</a></small></h1>
	<small><?php echo isset($error['update_languages']) ? $error['update_languages'] : ''; ?></small>
	<ol class="breadcrumb">
		<li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
	</ol>
</section>
<section class="content">
	<!-- Main row -->

	<div class="row">
		<div class="col-md-10">

			<!-- general form elements -->
			<div class="box box-primary">
				<div class="box-header with-border">
				</div><!-- /.box-header -->
				<!-- form start -->
				<form id="edit_languages_form" method="post" enctype="multipart/form-data">
					<div class="box-body">
				    	<div class="row">
					  	  <div class="form-group">
                               <div class="col-md-6">
									<label for="exampleInputEmail1">Crop</label><i class="text-danger asterik">*</i>
									<input type="text" class="form-control" name="crop" value="<?php echo $res[0]['crop']; ?>">
								</div>
                                <div class="col-md-6">
									<label for="exampleInputEmail1">Price</label><i class="text-danger asterik">*</i>
									<input type="number" class="form-control" name="price" value="<?php echo $res[0]['price']; ?>">
								</div>
                            </div>
                         </div>
                         <br>
                         <div class="row">
					  	  <div class="form-group">
                               <div class="col-md-6">
									<label for="exampleInputEmail1">Daily Income</label><i class="text-danger asterik">*</i>
									<input type="number" class="form-control" name="daily_income" value="<?php echo $res[0]['daily_income']; ?>">
								</div>
                                <div class="col-md-6">
									<label for="exampleInputEmail1">Total Income</label><i class="text-danger asterik">*</i>
									<input type="number" class="form-control" name="total_income" value="<?php echo $res[0]['total_income']; ?>">
								</div>
                            </div>
                         </div>
                         <br>
                         <div class="row">
					  	  <div class="form-group">
                               <div class="col-md-6">
									<label for="exampleInputEmail1">Invite Bonus</label><i class="text-danger asterik">*</i>
									<input type="number" class="form-control" name="invite_bonus" value="<?php echo $res[0]['invite_bonus']; ?>">
								</div>
                                <div class="col-md-6">
									<label for="exampleInputEmail1">Validity</label><i class="text-danger asterik">*</i>
									<input type="number" class="form-control" name="validity" value="<?php echo $res[0]['validity']; ?>">
								</div>
                            </div>
                         </div>
                     </div>
					<div class="box-footer">
						<button type="submit" class="btn btn-primary" name="btnEdit">Update</button>

					</div>
				</form>
			</div><!-- /.box -->
		</div>
	</div>
</section>

<div class="separator"> </div>
<?php $db->disconnect(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>