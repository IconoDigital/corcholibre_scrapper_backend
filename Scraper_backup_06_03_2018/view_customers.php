<?php 
session_start();
include("includes/Library.php");
include("includes/config.php");
include("includes/DatabaseConnection.php");
include("permission.php");
$error=0;
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

    <title>View Customer</title>

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
                              All Clients
                          </header>
                          <div class="panel-body">
                                <div class="adv-table">
                                    <table  class="display table table-bordered table-striped" id="example">
                                      <thead>
                                      <tr>
                                          <th>Name</th>
                                          <th>Affiliate</th>
                                          <th>Email</th>
										  <th>Phone</th>
                                          <th>Address</th>                                          
                                          <th class="hidden-phone">State</th>
                                          <th class="hidden-phone">City</th>
										  <th class="hidden-phone">Country</th>
										  <th class="hidden-phone">Zip</th>
                                          <th class="hidden-phone">Status</th>
                                          <th>Action</th>
                                      </tr>
                                      </thead>
                                      <tbody>
									  <?php 
									  $i=0;
									  if($_SESSION["user_type"]=='affiliate')
									  $sql="SELECT c.*, (SELECT a.business_name FROM f2c48_affiliate a WHERE a.id=c.affiliate_id) as business_name FROM customers c WHERE affiliate_id='".$_SESSION["user_id"]."'";
									  else
									  $sql="SELECT c.*, (SELECT a.business_name FROM f2c48_affiliate a WHERE a.id=c.affiliate_id) as business_name FROM customers c";
									  $rs=mysqli_query($mysqli,$sql) or die_u(mysqli_error($mysqli));
									  
									  while($res=mysqli_fetch_array($rs))
									  {
										if($res["status"]==0)
											$status="Not Verified";
										if($res["status"]==1)
											$status="Verified";
									  ?>
                                      <tr class="<?php if($i==0) echo "gradeX"; else if($i==1) echo "gradeC"; else echo "gradeA";?>">
                                          <td><?php echo $res["name"];?></td>
                                          <td><?php if($res["affiliate_id"]!=0) echo $res["business_name"]; else echo "Pre-paid";?></td>
										  <td><?php echo $res["email"];?></td>
										  <td><?php echo $res["phone"];?></td>
                                          <td><?php echo $res["address"];?></td>
										  <td class="hidden-phone"><?php echo $res["state"];?></td>
										  <td class="hidden-phone"><?php echo $res["city"];?></td>
                                          <td class="hidden-phone"><?php echo $res["country"];?></td>
                                          <td class="hidden-phone"><?php echo $res["zip"];?></td>
                                          <td class="hidden-phone"><?php echo $status;?></td>
                                          <td class="center">
										  <a href="view_customer.php?customer_id=<?php echo $res["id"]?>"><button type="button" class="btn btn-primary">Details</button></a>
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
                                          <th>Name</th>
										  <th>Affiliate</th>
                                          <th>Email</th>
										  <th>Phone</th>
                                          <th>Address</th>                                          
                                          <th class="hidden-phone">State</th>
                                          <th class="hidden-phone">City</th>
										  <th class="hidden-phone">Country</th>
										  <th class="hidden-phone">Zip</th>
                                          <th class="hidden-phone">Status</th>
                                          <th>Action</th>
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
      </script>

  </body>

</html>
