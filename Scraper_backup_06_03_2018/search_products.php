<?php 
error_reporting(E_ERROR);
session_start();
include("includes/Library.php");
include("includes/config.php");
include("includes/DatabaseConnection.php");
include("permission.php");
$hide=0;

/*if (isset($_POST['add_catagory'])) 
{
 if((isset($_POST["cat_keyword"]) && $_POST["unit_keyword"] != "") )
 {
	  $insert_sql = "INSERT INTO product SET company_name='".$_POST["company_name"]."',banner_name='".$_POST["banner_name"]."',banner_image_link='".$_FILES["upload_banner"]['name']."',valid_upto='".$_POST["valid_upto"]."'";
      $insert_que = mysqli_query($mysqli,$insert_sql) or die_u(mysqli_error($mysqli));
	    if ($insert_que) {
			/*redirecting to view_doctors
			//$_SESSION["doc_added"]=1;
			//header("Location:view_doctors.php");
          $fancy_message=1 ;
		  $fancy_message_title="Success";
		  $fancy_message_text="Data inserted Successfully";//echo "<script type='text/javascript'>alert('submitted successfully!');</script>";
         }
        else
        {
          $error=1; //echo "<script type='text/javascript'>alert('failed!');</script>";
        }
 }
}*/

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

    <title>Search Products</title>

   <?php include("header.php");?>
      <!--header end-->
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
				   <form action="" accept-charset="utf-8" method="post" >
				     <div class="form-group" style="margin-bottom:15px;">
                                <div class="col-md-2 col-sm-12"><label for="name">Search Product: </label></div>
								
                                <div class="col-md-6 col-sm-7"> <input type="text" class="form-control" id="search_product" aria-describedby="emailHelp" name="search_product" placeholder="Find product " required>
                                   </div>
								   
								<div class="col-md-4 col-sm-5"><button class="btn btn-primary" type="submit" id="submit" name="find_product" >Submit</button> </div>
								   
                        </div>				 
					 </form>
					 <br>
					<?php
if (isset($_POST['find_product'])) 
{
 if((isset($_POST["search_product"]) && $_POST["search_product"] != "") )
 {
	 //echo "a";
        $i=0;
		$search_item = $_POST["search_product"];
        $sql = "SELECT * FROM product where product_name like '%".$search_item."%' or product_desc like '%".$search_item."%' or brand_name like '%".$search_item."%' or category_keyword like '%".$search_item."%' or quantity_keyword like '%".$search_item."%' ";
        $que = mysqli_query($mysqli,$sql) or die_u(mysqli_error($mysqli));  
		 if(mysqli_num_rows($que)>0)
		 {
			
           $hide=1;
?>		   
					 
					<br>
					<br>
					<div style="display:<?php if($hide==0) echo "none;";?> " >
                      <section class="panel">	    
                          <header class="panel-heading">
                              All Products
							  
							  <!--<div class="pull-right">
							     <a href="add_products.php" class="btn btn-primary btn-success" ><i class="fa fa-plus-square"></i> ADD </a>
							  </div> -->
                          </header>
                          <div class="panel-body">
                                <div class="adv-table">
                                    <table  class="display table table-bordered table-striped" id="example">
                                      <thead>
                                      <tr>
									      <th>SELECT <input type="checkbox" id="chk_new"  onclick="checkAll('chk');" checked > </th>
                                          <th>ID</th>
                                          <th>NAME</th>
                                          <th>DESC</th>
                                          <th>BRAND</th>
										  <th>UNIT</th>
                                          <th>PRICE</th> 
                                          <th>OFFER PRICE</th>
										  <th>OFFER TITLE</th>
										  <!--<th>OFFER CONDITIONS</th>-->
										  <th>SOURCE</th>
										  <th>VALID UNTIL</th>
										  <th>Picture</th>
                                          <th>CATAGORY</th>
                                          <th>QUANTITY</th>
                                      </tr>
                                      </thead>
                                      <tbody>
<?php
           while($res=mysqli_fetch_array($que))
          {
	
 ?>
                                     <tr class="<?php if($i==0) echo "gradeX"; else if($i==1) echo "gradeC"; else echo "gradeA";?>">
									      <td><input type="checkbox" name="chkbox" id="chk<?php echo $res["product_id"];?>" data-id="<?php echo $res["product_id"];?>"  checked />&nbsp;</td>
                                          <td><?php echo $res["product_id"];?></td>
                                          <td><?php echo $res["product_name"];?></td>
                                          <td><?php echo $res["product_desc"];?></td>
										  <td><?php echo $res["brand_name"];?></td>
										  <td><?php echo $res["unit"];?></td>
                                          <td><?php echo $res["price"];?></td>
										  <td><?php echo $res["offer_price"];?></td>
										  <td><?php echo $res["offer_title"];?></td>
                                          <!--<td><?php //echo $res["offer_conditions"];?></td> -->
										  <td><?php echo $res["source"];?></td>
										  <td><?php echo $res["valid_until"];?></td>
										  <td><?php if($res["source"]=="tottus") $link="http://52.3.144.26/scraper/product_images/".$res["product_id"].".jpg";
										  else
											 $link="http://52.3.144.26/scraper/product_images/".$res["product_id"].".jpg"; 
										  ?>
										  <a href="<?php echo $link;?>"><img src="<?php echo $link;?>" style="width:100px;"/></a>
										  </td>
										  <td><?php echo $res["category_keyword"];?></td>
										  <td><?php echo $res["quantity_keyword"];?></td>
                                          <!--<td class="center">
										  
										  <a href="edit_products.php?id=<?php //echo $res["product_id"]; ?>" class="btn btn-primary" style="margin-bottom:15px;"><i class="icon-edit"></i></a>
										  
										  <button type="button" class="btn btn-primary btn-danger" data-id="<?php //echo $res["product_id"]; ?>" ><i class="icon-trash"></i></button>
										  </td> -->
                                      </tr>
                                      <?php 
									  $i++;
									  if($i==3)
										  $i=0;							   
									    }
		                              ?>
                                      </tbody>
                                      <tfoot>
                                     <tr>
									      <th>SELECT</th>
                                          <th>ID</th>
                                          <th>NAME</th>
										  <th>DESC</th>
                                          <th>BRAND</th>
										  <th>UNIT</th>
                                          <th>PRICE</th> 
                                          <th>OFFER PRICE</th>
										  <th>OFFER TITLE</th>
										  <!-- <th>OFFER CONDITIONS</th> -->
										  <th>SOURCE</th>
										  <th>VALID UNTIL</th>
										   <th>Picture</th>
                                          <!-- <th class="hidden-phone">State</th> -->
                                          <th>CATAGORY</th>
                                          <th>QUANTITY</th>
                                      </tr>
                                      </tfoot>
                          </table>
                                </div>
							
							
						  
						  <?php //if($message!="") echo $message;?>
						  <div class="col-lg-8 col-lg-offset-2 col-sm-12">
						  <div class="row">
						  <div class="col-lg-12">
						  <form action="" accept-charset="utf-8" method="post">
							<br>
							<br>
							<br>
							   <!--    COPANY NAME FIELD      -->
                                       <div class="form-group" style="margin-bottom:15px;">
                                            <div class="col-md-3 col-sm-4">
                                                <label for="name">Category Keyword: </label>
                                            </div>
                                            <div class="col-md-8 col-sm-8">
                                                <input type="text" class="form-control" id="cat_keyword" aria-describedby="emailHelp" name="cat_keyword" placeholder="Enter category  " required>
                                            </div>
                                        </div>
										<br>
										<br>
										 <!-- BANNER NAME FIELD-->
										<div class="form-group" style="margin-bottom:15px;">
                                            <div class="col-md-3 col-sm-4">
                                                <label for="name">Quantity Keyword: </label>
                                            </div>
                                            <div class="col-md-8 col-sm-8">
                                                <input type="text" class="form-control" id="quantity_keyword" aria-describedby="emailHelp" name="quantity_keyword" placeholder="Enter quantity " >
                                            </div>
                                        </div>
										<br>
										<br>
							<!-- &nbsp &nbsp Website LINK: <input type="text" name="name" > &nbsp &nbsp <input type="text" name="name" > &nbsp <input type="submit" name="submit" value="Submit"> -->
							      <div class="form-group" style="margin-bottom:15px;">
                                            <div class="col-md-3 col-sm-4">&nbsp;</div>
											<div class="col-md-8 col-sm-8"><button class="btn btn-primary" type="button" id="add_catagory" name="add_catagory" >Submit</button></div>
                                    </div>  
							 </form>
						  </div>				  
                          </div>
						 </div>
						
                        </div>
                      </section>
					 </div> 
					 <?php }
                              }
                            }
								?>  
                  </div>
              </div>
             
			   <!-- page end-->
          </section>
      </section>
      <!--main content end-->
  </section>


  
    <?php 
	include("footer.php");
	?>
<script type="text/javascript" charset="utf-8">
         /* $(document).ready(function() {
              $('#example').dataTable( {
                  "aaSorting": [[ 4, "desc" ]]
              } );
          } );*/
		  
		   $(document).ready(function() {
                     $(".btn-danger").click( function(){  
					 //$(document).on('click', '#btn_delete', function(){  
                              window.id_delete=$(this).data("id");  
                               //alert(window.id_delete);
                              if(confirm("Are you sure want to delete this?"))  
                              {  
                                   $.ajax({  
                                        url:"delete.php",  
                                        method:"POST",  
                                        data:{delete_id:window.id_delete},  
                                        dataType:"text",  
                                        success:function(data){  
                                               alert('product deleted successfully');
											   location.reload();
											   //$(this).closest('tr').remove(); 
                                        }  
                                   }); 
                                  
                              }  
                         });
						 
					$("#add_catagory").click( function(){ 
					   var cat_keyword=$("#cat_keyword").val();
					   //alert(cat_keyword);
					   var quantity_keyword=$("#quantity_keyword").val();
					   //alert(unit_keyword);
					   if(cat_keyword!=""){
					    $('#example').find('tr').each(function () {
							var row = $(this);
							if (row.find('input[type="checkbox"]').is(':checked')) {							
								 id= row.find('input[type="checkbox"]').attr("data-id");
								 
								 $.ajax({  
                                        url:"add_catagory.php",  
                                        method:"POST",  
                                        data:{product_id:id,cat_keyword:cat_keyword,quantity_keyword:quantity_keyword},  
                                        dataType:"text",  
                                        success:function(data){  
                                               //alert('product updated successfully');
											   //location.reload();
											   //$(this).closest('tr').remove(); 
                                        }  
                                   });
								//alert(a);
							 }
						 });
						 location.reload();
					    }
						else{
							alert("Please give any category");
						}				
					});	 
			 } );
			 

       function checkAll(checkId){
        var inputs = document.getElementsByName("chkbox");
		//alert(checkId)
        for (var i = 0; i < inputs.length; i++) { 
            if (inputs[i].type == "checkbox" /*&& inputs[i].id == checkId*/) { 
                if(inputs[i].checked == true) {
                    inputs[i].checked = false ;
                } else if (inputs[i].checked == false ) {
                    inputs[i].checked = true ;
                }
            }  
        }  
    }
      </script>

  </body>

</html>