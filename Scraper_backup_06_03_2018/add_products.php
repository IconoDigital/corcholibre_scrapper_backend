<?php
session_start();
include("includes/Library.php");
include("includes/config.php");
include("includes/DatabaseConnection.php");
include("permission.php");
$error=0;
/*SUBMIT THE DATA*/
if(
    (isset($_POST["product_name"]) && $_POST["product_name"] != "") ||
    (isset($_POST["product_desc"]) && $_POST["product_desc"] != "") ||
    (isset($_POST["brand_name"]) && $_POST["brand_name"] != "" )||
    (isset($_POST["unit"]) && $_POST["unit"] != "") ||
    (isset($_POST["price"]) && $_POST["price"] != "") ||
    (isset($_POST["offer_price"]) && $_POST["offer_price"] != "" ) ||
    (isset($_POST["offer_title"]) && $_POST["offer_title"] != "" ) ||
    (isset($_POST["offer_conditions"]) && $_POST["offer_conditions"] != "" ) ||
    (isset($_POST["source"]) && $_POST["source"] != "" ) ||
    (isset($_POST["valid_until"]) && $_POST["valid_until"] != "" ) 
      
){
        $i=0;
		$filename_for_db = "";
        if($_POST["source"]==1){$source="plazavea";}else{$source="tottus";}
        $insert_sql = "INSERT INTO product SET product_name='".$_POST["product_name"]."',product_desc='".$_POST["product_desc"]."',brand_name='".$_POST["brand_name"]."',unit='".$_POST["unit"]."',price='".$_POST["price"]."',offer_price='".$_POST["offer_price"]."',offer_title='".$_POST["offer_title"]."',offer_conditions='".$_POST["offer_conditions"]."',source='".$source."',valid_until='".$_POST["valid_until"]."'";
        $insert_que = mysqli_query($mysqli,$insert_sql) or die_u(mysqli_error($mysqli));
	    if ($insert_que) {
          echo "<script type='text/javascript'>alert('submitted successfully!');</script>";
         }
        else
        {
          echo "<script type='text/javascript'>alert('failed!');</script>";
        }
  
}

?>
<!DOCTYPE html>
<html lang="en">
  
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Codomotive">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="img/favicon.ico">

    <title>View Products</title>

   <?php include("header.php");?>
      <!--header end-->
</head>
<body>
      <!--sidebar start-->
	<?php 
	include("admin-sidebar.php");
	?>
	 <!--sidebar end-->
      <!--main content start-->
	   <section id="main-content">
        <section class="wrapper">
           <!-- page start-->
		   <div class="row">
              <div class="col-lg-12">
			     <section class="panel">
			        <header class="panel-heading">
                      Add Product 
                    </header>
                   <div class="panel-body">
                            <div class="col-lg-8 col-lg-offset-2 col-sm-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                      <form method="post" enctype='multipart/form-data' class="form-horizontal">
                                        <!--    PRODUCT NAME FIELD      -->
                                       <div class="form-group" style="margin-bottom:15px;">
                                            <div class="col-md-3 col-sm-4">
                                                <label for="name">Product Name: </label>
                                            </div>
                                            <div class="col-md-8 col-sm-8">
                                                <input type="text" class="form-control" id="product_name" aria-describedby="emailHelp" name="product_name" placeholder="Enter product name " required>
                                            </div>
                                        </div>
										   <!--   PRODUCT DESC FIELD      -->
                                       <div class="form-group" style="margin-bottom:15px;">
                                           <div class="col-md-3 col-sm-4"> 
                                                <label for="PDESC">Product Description</label>
                                            </div>
                                            <div class="col-md-8 col-sm-8">
                                                <input type="text" class="form-control" id="product_desc" aria-describedby="emailHelp" name="product_desc" placeholder="Enter product description" >
                                            </div>
                                        </div>  
										   <!--    BRAND NAME FIELD      -->
                                       <div class="form-group" style="margin-bottom:15px;">
                                           <div class="col-md-3 col-sm-4"> 
                                                <label for="brandname">Brand Name</label>
                                            </div>
                                            <div class="col-md-8 col-sm-8">
                                                <input type="text" class="form-control" id="brand_name" aria-describedby="emailHelp" name="brand_name" placeholder="Enter Brand Name" required>
                                            </div>
                                        </div>  
										   <!--    UNIT FIELD      -->
                                       <div class="form-group" style="margin-bottom:15px;">
                                           <div class="col-md-3 col-sm-4"> 
                                                <label for="unit">Unit</label>
                                            </div>
                                            <div class="col-md-8 col-sm-8">
                                                <input type="text" class="form-control" id="unit" aria-describedby="emailHelp" name="unit" placeholder="Enter Unit" >
                                            </div>
                                        </div>  
										   <!--    PRICE FIELD      -->
                                       <div class="form-group" style="margin-bottom:15px;">
                                           <div class="col-md-3 col-sm-4"> 
                                                <label for="price">Price</label>
                                            </div>
                                            <div class="col-md-8 col-sm-8">
                                                <input type="text" class="form-control" id="price" aria-describedby="emailHelp" name="price" placeholder="Enter price" required>
                                            </div>
                                        </div>  
										   <!--    OFFER PRICE FIELD      -->
                                       <div class="form-group" style="margin-bottom:15px;">
                                           <div class="col-md-3 col-sm-4"> 
                                                <label for="Offerprice">Offer Price</label>
                                            </div>
                                            <div class="col-md-8 col-sm-8">
                                                <input type="text" class="form-control" id="offer_price" aria-describedby="emailHelp" name="offer_price" placeholder="Enter Offer Price">
                                            </div>
                                        </div>  
										   <!--    OFFER TITLE FIELD      -->
                                       <div class="form-group" style="margin-bottom:15px;">
                                           <div class="col-md-3 col-sm-4"> 
                                                <label for="offertitle">Offer Title</label>
                                            </div>
                                            <div class="col-md-8 col-sm-8">
                                                <input type="text" class="form-control" id="offer_title" aria-describedby="emailHelp" name="offer_title" placeholder="Enter Offer Title" >
                                            </div>
                                        </div>  
										   <!--    OFFER CONDITIONS FIELD      -->
                                       <div class="form-group" style="margin-bottom:15px;">
                                           <div class="col-md-3 col-sm-4"> 
                                                <label for="offerconditions">Offer Condititons</label>
                                            </div>
                                            <div class="col-md-8 col-sm-8">
                                                <input type="text" class="form-control" id="offer_conditions" aria-describedby="emailHelp" name="offer_conditions" placeholder="Enter Offer Condititons">
                                            </div>
                                        </div>  
										   <!--    TIMESTAMP FIELD      
                                       <div class="form-group" style="margin-bottom:15px;">
                                           <div class="col-md-3 col-sm-4"> 
                                                <label for="time">Timestamp</label>
                                            </div>
                                            <div class="col-md-8 col-sm-8">
                                                <input type="text" class="form-control" id="time_stamp" aria-describedby="emailHelp" name="time_stamp" placeholder="Enter Time" required>
                                            </div>
                                        </div>  -->
                                    <!--    SOURCE FIELD      -->
                                        <div class="form-group" style="margin-bottom:15px;">
                                            <div class="col-md-3 col-sm-4">
                                                <label for="exampleSelect1">Source</label>
                                            </div>
                                            <div class="col-md-8 col-sm-8">
                                                <select class="form-control" id="source" name="source" required>
                                                <option>Select Option</option>
                                                <option value="0">Tottus</option>
                                                <option value="1">Plazavea</option>
                                                </select>
                                            </div>
                                            
                                        </div>
                                   
                                     <!--    VALID UNTIL FIELD      -->
                                       <div class="form-group" style="margin-bottom:15px;">
                                            <div class="col-md-3 col-sm-4">
                                                <label for="exampleInputEmail1">Valid Until</label>
                                            </div>
                                            <div class="col-md-8 col-sm-8">
                                                <input type="text" class="form-control" id="valid_until" aria-describedby="emailHelp" name="valid_until" placeholder="Enter Valid Until">
                                            </div>
                                        </div>    
                                      <!--    AMOUNT FIELD    
                                       <div class="form-group" style="margin-bottom:15px;">
                                            <div class="col-md-3 col-sm-4">
                                                <label for="exampleInputEmail1">Amount</label>
                                            </div>
                                            <div class="col-md-8 col-sm-8">
                                                <input type="number" class="form-control" id="amount" aria-describedby="emailHelp" name="amount" placeholder="Enter Amount" required>
                                            </div>
                                        </div>    -->
                      
                                        <div class="form-group" style="margin-bottom:15px;">
                                            <div class="col-md-3 col-sm-4">&nbsp;</div>
											<div class="col-md-8 col-sm-8"><button class="btn btn-primary" type="submit" id="submit" name="submit">Submit</button></div>
                                        </div>  

                                     </form>
                                    </div>
                                </div>
                            </div>
			          </div>			
		         </section>	
			  </div>
	     </div>
	   <!-- page end-->
      </section>
      <!--main content end-->
  </section>
    <?php
        include("footer.php");
        
    ?>

    <!-- SINGLE DATE PICKER -->
	<script type="text/javascript">
    
$(document).ready(function(){
    $('input[name="valid_until"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
        "format": "YYYY-MM-DD",
        "separator": "-",
      }

    });
});
    
    

</script>

 </body>

</html>