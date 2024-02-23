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

	$products = $db->escapeString(($_POST['products']));
	$price = $db->escapeString(($_POST['price']));
	$daily_income = $db->escapeString(($_POST['daily_income']));
	$monthly_income = $db->escapeString(($_POST['monthly_income']));
	$invite_bonus = $db->escapeString(($_POST['invite_bonus']));
	$daily_quantity = $db->escapeString(($_POST['daily_quantity']));
	$error = array();

    if (!empty($products) && !empty($price) && !empty($daily_income) && !empty($monthly_income) && !empty($invite_bonus) && !empty($daily_quantity)) 
    {
		$sql_query = "UPDATE plan SET products='$products',price='$price',daily_income='$daily_income',monthly_income='$monthly_income',invite_bonus='$invite_bonus',daily_quantity='$daily_quantity' WHERE id =  $ID";
		$db->sql($sql_query);
		$result = $db->getResult();             
		if (!empty($result)) {
			$error['update_languages'] = " <span class='label label-danger'>Failed</span>";
		} else {
			$error['update_languages'] = " <span class='label label-success'>Plans Updated Successfully</span>";
		}
	
		if ($_FILES['image']['size'] != 0 && $_FILES['image']['error'] == 0 && !empty($_FILES['image'])) {
		
			$extension = pathinfo($_FILES["image"]["name"])['extension'];
	
			$result = $fn->validate_image($_FILES["image"]);
			$target_path = 'upload/images/';
			
			$filename = microtime(true) . '.' . strtolower($extension);
			$full_path = $target_path . "" . $filename;
			if (!move_uploaded_file($_FILES["image"]["tmp_name"], $full_path)) {
				echo '<p class="alert alert-danger">Can not upload image.</p>';
				return false;
				exit();
			}
			if (!empty($old_image) && file_exists($old_image)) {
				unlink($old_image);
			}
	
			$upload_image = 'upload/images/' . $filename;
			$sql = "UPDATE plan SET `image`='$upload_image' WHERE `id`='$ID'";
			$db->sql($sql);
	
			$update_result = $db->getResult();
			if (!empty($update_result)) {
				$update_result = 0;
			} else {
				$update_result = 1;
			}
	
			if ($update_result == 1) {
				$error['update_languages'] = " <section class='content-header'><span class='label label-success'>Jobs updated Successfully</span></section>";
			} else {
				$error['update_languages'] = " <span class='label label-danger'>Failed to update</span>";
			}
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
									<label for="exampleInputEmail1">Products</label><i class="text-danger asterik">*</i>
									<input type="text" class="form-control" name="products" value="<?php echo $res[0]['products']; ?>">
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
									<label for="exampleInputEmail1">Monthly Income</label><i class="text-danger asterik">*</i>
									<input type="number" class="form-control" name="monthly_income" value="<?php echo $res[0]['monthly_income']; ?>">
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
									<label for="exampleInputEmail1">Daily Quantity</label><i class="text-danger asterik">*</i>
									<input type="number" class="form-control" name="daily_quantity" value="<?php echo $res[0]['daily_quantity']; ?>">
								</div>
                            </div>
                         </div>
						 <br>
						<div class="row">
						 <div class="form-group">
                                   <div class="col-md-6">
                                    <label for="exampleInputFile">Image</label> <i class="text-danger asterik">*</i><?php echo isset($error['image']) ? $error['image'] : ''; ?>
                                    <input type="file" name="image" onchange="readURL(this);" accept="image/png, image/jpeg" id="image" /><br>
                                    <img id="blah" src="<?php echo $res[0]['image']; ?>" alt="" width="150" height="200" <?php echo empty($res[0]['image']) ? 'style="display: none;"' : ''; ?> />
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