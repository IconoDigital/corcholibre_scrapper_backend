 <!--
 <style>
 .selected_panel{
	 border: solid 1px;
 }
 </style>
 <div class="row state-overview">
                  <div class="col-lg-4 col-sm-8">
					<a class="view_history" href="#">
                      <section class="panel">
                          <div class="symbol terques">
                              <i class="icon-user"></i>
                          </div>
                          <div class="value">
						  <?php 
						  $package=$sub_res["package"];
							if($package=="10 Agent Subscription")
								$max_agent=10;
							if($package=="20 Agent Subscription")
								$max_agent=20;
							if($package=="Single Agent Subscription")
								$max_agent=1;
						  
						  $sql="SELECT count(*) as cnt FROM users WHERE parent_user='".$_SESSION["user_id"]."'";
						  $rs=mysqli_query($mysqli,$sql) or die_u(mysqli_error($mysqli));
						  $res=mysqli_fetch_array($rs);
						  ?>
                              <h1><?php echo $res["cnt"]."/".$max_agent;?></h1>
                              <p>Agents</p>
                          </div>
                      </section></a>
                  </div>
                  <div class="col-lg-4 col-sm-8">
				  <a class="view_clients" href="#">
                      <section class="panel">
                          <div class="symbol red">
                              <i class="icon-group"></i>
                          </div>
                          <div class="value">
						  <?php 
						  $sql="SELECT count(*) as cnt FROM users WHERE parent_user in (SELECT user_id FROM users WHERE parent_user='".$_SESSION['user_id']."')";
						  $rs=mysqli_query($mysqli,$sql) or die_u(mysqli_error($mysqli));
						  $res=mysqli_fetch_array($rs);
						  ?>
                              <h1><?php echo $res["cnt"]?></h1>
                              <p>Clients</p>
                          </div>
                      </section>
					  </a>
                  </div>
                  
				   <div class="col-lg-4 col-sm-8">
				   <a href="#">
                      <section class="panel">
                          <div class="symbol yellow">
                              <i class="icon-shopping-cart"></i>
                          </div>
                          <div class="value">
                              <h1><?php echo $sub_res["subscription_left"];?></h1>
                              <p>Days Left</p>
                          </div>
                      </section>
					  </a>
                  </div>
              </div>
			  <script>
			  $(function(){
				  $(".view_clients").on("click",function(e){
					  e.preventDefault();
					  $(".selected_panel").removeClass("selected_panel");
					  $("#view_history").hide();
					  $("#view_clients").show();
					  $(this).children(".panel").addClass("selected_panel");
					  
				  });
				  $(".view_history").on("click",function(e){
					  e.preventDefault();
					  $(".selected_panel").removeClass("selected_panel");
					  $("#view_clients").hide();
					  $("#view_history").show();
					   $(this).children(".panel").addClass("selected_panel");
				  });
			  });
			  </script>

<div class="row">
<div id="view_history" class="col-lg-12">
<section class="panel">
<header class="panel-heading">All Agents
</header>
<div class="panel-body">
     <div id="" class="adv-table">
                                       <table  class="display table table-bordered table-striped" id="client_table">
                                      <thead>
                                      <tr>
                                          <th>Name</th>
                                          <th>Email</th>
                                          <th class="hidden-phone">Phone</th>
                                          <th class="hidden-phone">No of Clients</th>
                                          <th>Action</th>
                                      </tr>
                                      </thead>
                                      <tbody>
									  <?php 
									  $i=0;
									  $sql="SELECT * FROM users WHERE user_type='agent' AND parent_user=".$_SESSION['user_id']."";
									  $rs=mysqli_query($mysqli,$sql) or die_u(mysqli_error($mysqli));
									  
									  while($res=mysqli_fetch_array($rs))
									  {
										  $clientsql="SELECT count(*) as cnt FROM users WHERE parent_user='".$res["user_id"]."'";
										  $clientrs=mysqli_query($mysqli,$clientsql) or die_u(mysqli_error($mysqli));
										  $client=mysqli_fetch_array($clientrs);
										  
									  ?>
                                      <tr class="<?php if($i==0) echo "gradeX"; else if($i==1) echo "gradeC"; else echo "gradeA";?>">
                                          <td><?php echo $res["name"];?></td>
                                          <td><?php echo $res["email"];?></td>
                                          <td class="hidden-phone"><?php echo $res["phone"];?></td>
                                          <td class="center hidden-phone"><?php echo $client["cnt"];?></td>
                                          <td class="center">
										  <a href="agent_details.php?agent_id=<?php echo $res["user_id"];?>"><button type="button" class="btn btn-primary">View Agent</button></a>
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
                                          <th>Email</th>
                                          <th class="hidden-phone">Phone</th>
                                          <th class="hidden-phone">No of Clients</th>
                                          <th>Action</th>
                                      </tr>
                                      </tfoot>
                          </table>
                               
                                </div>
                         
</div>
</section>
</div>

<div style="display:none;" id="view_clients" class="col-lg-12">
<section class="panel">
                          <header class="panel-heading">
                              All clients
                          </header>
                          <div class="panel-body">
                                <div id="" class="adv-table">
                                    <table  class="display table table-bordered table-striped" id="client_table">
                                      <thead>
                                      <tr>
                                          <th>Name</th>
                                          <th>Email</th>
                                          <th class="hidden-phone">Phone</th>
                                          <th class="hidden-phone">Deal Status</th>
                                          
                                      </tr>
                                      </thead>
                                      <tbody>
									  <?php 
									  $i=0;
									  $sql="SELECT * FROM users WHERE user_type='client' AND parent_user in (SELECT user_id FROM users WHERE parent_user=".$_SESSION['user_id'].")";
									  $rs=mysqli_query($mysqli,$sql) or die_u(mysqli_error($mysqli));
									  
									  while($res=mysqli_fetch_array($rs))
									  {
										  $dealsql="SELECT * FROM deal WHERE client_id='".$res["user_id"]."' ORDER BY timestamp DESC";
										  $dealrs=mysqli_query($mysqli,$dealsql) or die_u(mysqli_error($mysqli));
										  $deal=mysqli_fetch_array($dealrs);
										  if($deal['progress']==1)
											  $progress="Home Search";
										  if($deal['progress']==2)
											  $progress="Offer Process";
										  if($deal['progress']==3)
											  $progress="Acceptance";
										  if($deal['progress']==4)
											  $progress="Contingency";
										  if($deal['progress']==5)
											  $progress="Sale Pending";
										  if($deal['progress']==6)
											  $progress="Closing";
									  ?>
                                      <tr class="<?php if($i==0) echo "gradeX"; else if($i==1) echo "gradeC"; else echo "gradeA";?>">
                                          <td><?php echo $res["name"];?></td>
                                          <td><?php echo $res["email"];?></td>
                                          <td class="hidden-phone"><?php echo $res["phone"];?></td>
                                          <td class="center hidden-phone"><?php echo $progress;?></td>
                                         
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
                                          <th>Email</th>
                                          <th class="hidden-phone">Phone</th>
                                          <th class="hidden-phone">Deal Status</th>
                                          
                                      </tr>
                                      </tfoot>
                          </table>
                                </div>
                          </div>
                      </section>
</div>

</div>	

<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
$('#history_table').dataTable( {
"aaSorting": [[ 4, "desc" ]]
} );
$('#client_table').dataTable( {
"aaSorting": [[ 4, "desc" ]]
} );
} );
</script>	
-->