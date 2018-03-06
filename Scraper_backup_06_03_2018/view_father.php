<?php 
error_reporting(E_ERROR);
session_start();
include("includes/Library.php");
include("includes/config.php");
include("includes/DatabaseConnection.php");
include("permission.php");

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

    <title>View Father Links</title>

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
                              All Father Links
							  
							  <div class="pull-right">
							     <a href="add_tottus_list.php" class="btn btn-primary btn-success" ><i class="fa fa-plus-square"></i> ADD Tottus Link</a>
							     <a href="add_plazavea_list.php" class="btn btn-primary btn-success" ><i class="fa fa-plus-square"></i> ADD Plazavea Link</a>
							  </div>
                          </header>
                          <div class="panel-body">
                                <div class="adv-table">
                                    <table  class="display table table-bordered table-striped" id="example">
                                      <thead>
                                      <tr>
                                          <th>ID</th>
                                          <th>URL</th>
                                          <th>SOURCE</th>
                                      </tr>
                                      </thead>
                                      <tbody>
<?php
$i=0;
$sql = "SELECT * FROM father_urls;";
$result = mysqli_query($mysqli,$sql)or die(mysqli_error($mysqli)); //$conn->query($sql);

 while($res=mysqli_fetch_array($result))
{
 ?>
                                     <tr class="<?php if($i==0) echo "gradeX"; else if($i==1) echo "gradeC"; else echo "gradeA";?>">
                                          <td><?php echo $res["id"];?></td>
                                          <td><?php echo $res["url"];?></td>
										  <td><?php echo $res["source"];?></td>
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
                                          <th>URL</th>
                                          <th>SOURCE</th>
                                      </tr>
                                      </tfoot>
                          </table>
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
          $(document).ready(function() {
              $('#example').dataTable( {
                  "aaSorting": [[ 4, "desc" ]]
              } );
          } );
		  
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