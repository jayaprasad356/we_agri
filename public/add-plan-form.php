<?php
include_once('includes/functions.php');
$function = new functions;
include_once('includes/custom-functions.php');
$fn = new custom_functions;
?>
<?php
if (isset($_POST['btnAdd'])) {

        $crop = $db->escapeString(($_POST['crop']));
        $price = $db->escapeString(($_POST['price']));
        $daily_income = $db->escapeString(($_POST['daily_income']));
        $total_income = $db->escapeString(($_POST['total_income']));
        $invite_bonus = $db->escapeString(($_POST['invite_bonus']));
        $validity = $db->escapeString(($_POST['validity']));
        $error = array();
       
        if (empty($crop)) {
            $error['crop'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($price)) {
            $error['price'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($daily_income)) {
            $error['daily_income'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($total_income)) {
            $error['total_income'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($invite_bonus)) {
            $error['invite_bonus'] = " <span class='label label-danger'>Required!</span>";
        }
        if (empty($validity)) {
            $error['validity'] = " <span class='label label-danger'>Required!</span>";
        }
       
       
            // Validate and process the image upload
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

        $upload_image = 'upload/images/' . $filename;
        $sql = "INSERT INTO plan (crop, price,image,daily_income,total_income,invite_bonus,validity) VALUES ('$crop','$price', '$upload_image','$daily_income','$total_income','$invite_bonus','$validity')";
        $db->sql($sql);
    } else {
            $sql_query = "INSERT INTO plan (crop, price,daily_income,total_income,invite_bonus,validity) VALUES ('$crop','$price','$daily_income','$total_income','$invite_bonus','$validity')";
            $db->sql($sql);
        }
            $result = $db->getResult();
            if (!empty($result)) {
                $result = 0;
            } else {
                $result = 1;
            }

            if ($result == 1) {
                
                $error['add_languages'] = "<section class='content-header'>
                                                <span class='label label-success'>Plan Added Successfully</span> </section>";
            } else {
                $error['add_languages'] = " <span class='label label-danger'>Failed</span>";
            }
     }
        
?>
<section class="content-header">
    <h1>Add New Plan <small><a href='plan.php'> <i class='fa fa-angle-double-left'></i>&nbsp;&nbsp;&nbsp;Back to Plan</a></small></h1>

    <?php echo isset($error['add_languages']) ? $error['add_languages'] : ''; ?>
    <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-home"></i> Home</a></li>
    </ol>
    <hr />
</section>
<section class="content">
    <div class="row">
        <div class="col-md-10">
           
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">

                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form url="add-languages-form" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                       <div class="row">
                            <div class="form-group">
                                <div class='col-md-6'>
                                    <label for="exampleInputtitle">Crop</label> <i class="text-danger asterik">*</i>
                                    <input type="text" class="form-control" name="crop" required>
                                </div>
                                <div class='col-md-6'>
                                    <label for="exampleInputtitle">Price</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="price" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-6'>
                                    <label for="exampleInputtitle">Daily Income</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="daily_income" required>
                                </div>
                                <div class='col-md-6'>
                                    <label for="exampleInputtitle">Total Income</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="total_income" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                <div class='col-md-6'>
                                    <label for="exampleInputtitle">Invite Bonus</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="invite_bonus" required>
                                </div>
                                <div class='col-md-6'>
                                    <label for="exampleInputtitle">Validity</label> <i class="text-danger asterik">*</i>
                                    <input type="number" class="form-control" name="validity" required>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="form-group">
                                 <div class="col-md-6">
                                    <label for="exampleInputFile">Image</label> <i class="text-danger asterisk">*</i><?php echo isset($error['image']) ? $error['image'] : ''; ?>
                                    <input type="file" name="image" onchange="readURL(this);" accept="image/png, image/jpeg" id="image" required/><br>
                                    <img id="blah" src="#" alt="" style="display: none; max-height: 200px; max-width: 200px;" /> <!-- Adjust max-height and max-width as needed -->
                                 </div>
                            </div> 
                        </div>  
                        <br>
                    <!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="btnAdd">Submit</button>
                        <input type="reset" onClick="refreshPage()" class="btn-warning btn" value="Clear" />
                    </div>

                </form>
                <div id="result"></div>

            </div><!-- /.box -->
        </div>
    </div>
</section>
<div class="separator"> </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
<script>
    $('#add_leave_form').validate({

        ignore: [],
        debug: false,
        rules: {
        reason: "required",
            date: "required",
        }
    });
    $('#btnClear').on('click', function() {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].setData('');
        }
    });
</script>
<script>
    $(document).ready(function () {
        $('#user_id').select2({
        width: 'element',
        placeholder: 'Type in name to search',

    });
    });

    if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}

</script>

<!--code for page clear-->
<script>
    function refreshPage(){
    window.location.reload();
} 
</script>
<script>
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            // Set the source of the image to the selected file
            document.getElementById('blah').src = e.target.result;
            // Display the image by changing its style to block
            document.getElementById('blah').style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php $db->disconnect(); ?>
