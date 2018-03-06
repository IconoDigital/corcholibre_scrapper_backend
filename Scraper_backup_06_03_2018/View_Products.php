<?php 
error_reporting(E_ERROR);
session_start();
include("includes/Library.php");
include("includes/config.php");
include("includes/DatabaseConnection.php");
include("permission.php");

if((isset($_GET["page"])) && $_GET["page"]!="")
{
	$page_no=$_GET["page"];
}
else{
	$page_no=1;
}
//get total count
 $sql = "SELECT product_id,product_name,product_desc,brand_name,unit,price,offer_price,offer_title,offer_conditions,timestamp,source,category_id,valid_until FROM product ";
 $result = mysqli_query($mysqli,$sql)or die(mysqli_error($mysqli)); //$conn->query($sql);
 $total_count=ceil(mysqli_num_rows($result)/10);
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
                              All Products
							  
							  <div class="pull-right">
							     <a href="add_products.php" class="btn btn-primary btn-success" ><i class="fa fa-plus-square"></i> ADD </a>
							  </div>
                          </header>
                          <div class="panel-body">
                                <div class="adv-table">
                                    <table  class="display table table-bordered table-striped" id="example">
                                      <thead>
                                      <tr>
                                          <th>ID</th>
                                          <th>NAME</th>
                                          <th>DESC</th>
                                          <th>BRAND</th>
										  <th>UNIT</th>
                                          <th>PRICE</th> 
                                          <th>OFFER PRICE</th>
										  <th>OFFER TITLE</th>
										  <th>OFFER CONDITIONS</th>
										  <th>SOURCE</th>
										  <th>VALID UNTIL</th>
										  <th>Picture</th>
                                          <!-- <th class="hidden-phone">State</th> -->
                                          <th>Action</th>
                                      </tr>
                                      </thead>
                                      <tbody>
<?php
$i=0;
//$page_no=1;
$page_length=10;
$starts_form=($page_no-1)*$page_length;
$sql = "SELECT product_id,product_name,product_desc,brand_name,unit,price,offer_price,offer_title,offer_conditions,timestamp,source,category_id,valid_until FROM product limit ".$starts_form.",".$page_length;
$result = mysqli_query($mysqli,$sql)or die(mysqli_error($mysqli)); //$conn->query($sql);
//$total_count=mysqli_num_rows($result);
 while($res=mysqli_fetch_array($result))
{
 ?>
                                     <tr class="<?php if($i==0) echo "gradeX"; else if($i==1) echo "gradeC"; else echo "gradeA";?>">
                                          <td><?php echo $res["product_id"];?></td>
                                          <td><?php echo $res["product_name"];?></td>
                                          <td><?php echo $res["product_desc"];?></td>
										  <td><?php echo $res["brand_name"];?></td>
										  <td><?php echo $res["unit"];?></td>
                                          <td><?php echo $res["price"];?></td>
										  <td><?php echo $res["offer_price"];?></td>
										  <td><?php echo $res["offer_title"];?></td>
                                          <td><?php echo $res["offer_conditions"];?></td>
										  <td><?php echo $res["source"];?></td>
										  <td><?php echo $res["valid_until"];?></td>
										  <td><?php if($res["source"]=="tottus") $link="http://52.3.144.26/scraper/product_images/".$res["product_id"].".jpg";
										  else
											 $link="http://52.3.144.26/scraper/product_images/".$res["product_id"].".jpg"; 
										  ?>
										  <a href="<?php echo $link;?>"><img src="<?php echo $link;?>" style="width:100px;"/></a>
										  </td>
										  
                                          <td class="center">
										  
										  <a href="edit_products.php?id=<?php echo $res["product_id"]; ?>" class="btn btn-primary" style="margin-bottom:15px;"><i class="icon-edit"></i></a>
										  
										  <button type="button" class="btn btn-primary btn-danger" data-id="<?php echo $res["product_id"]; ?>" ><i class="icon-trash"></i></button>
										  </td>
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
                                          <th>ID</th>
                                          <th>NAME</th>
										  <th>DESC</th>
                                          <th>BRAND</th>
										  <th>UNIT</th>
                                          <th>PRICE</th> 
                                          <th>OFFER PRICE</th>
										  <th>OFFER TITLE</th>
										  <th>OFFER CONDITIONS</th>
										  <th>SOURCE</th>
										  <th>VALID UNTIL</th>
										   <th>Picture</th>
                                          <!-- <th class="hidden-phone">State</th> -->
                                          <th>Action</th>
                                      </tr>
                                      </tfoot>
                          </table>
                                </div>
								<div class="row">
								  <div class="col-sm-12 text-center">
									<nav aria-label="navigation">
									  
									  <div class="pagination">
										<a href="#" class="first" data-action="first">&laquo;</a>
										<a href="#" class="previous" data-action="previous">&lsaquo;</a>
										<input type="text" readonly="readonly" data-max-page="100" />
										<a href="#" class="next" data-action="next">&rsaquo;</a>
										<a href="#" class="last" data-action="last">&raquo;</a>
									</div>
									</nav>
								  </div>
								</div>
                          </div>
                      </section>
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
       /*   $(document).ready(function() {
              $('#example').dataTable( {
                  "aaSorting": [[ 4, "desc" ]]
              } );
          } );*/
		  
		  $(".pagination").jqPagination({
					 paged:function(page){
						// alert(page);
						//window.location="http://localhost/scraper/View_Products.php?page="+page;
						window.location="http://52.3.144.26/scraper/View_Products.php?page="+page;
						
					 },
					 max_page:<?php echo $total_count;?>,
					 current_page :<?php echo $page_no; ?>
				 })
				 
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
			 } );
      </script>

  </body>

</html>