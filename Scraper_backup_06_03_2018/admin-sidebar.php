 <?php 
 $cur_page=basename($_SERVER['PHP_SELF']);
 ?>
 <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu">
                  
				  <?php 
				  if($_SESSION["user_type"]=='admin')
				  {
				  ?>
				  
				  <li class="sub-menu <?php if($cur_page=="add_tottus.php" || $cur_page=="add_plazavea.php" || $cur_page=="add_wong.php" || $cur_page=="add_vivanda.php" || $cur_page=="add_plazavea_list.php" || $cur_page=="add_tottus_list.php" || $cur_page=="add_wong_list.php" || $cur_page=="add_vivanda_list.php") echo "active";?>">
                      <a href="javascript:;" class="">
                          <i class="icon-plus"></i>
                          <span>Add</span>
                          <span class="arrow"></span>
                      </a>
                      <ul class="sub">
                          <li <?php if($cur_page=="add_plazavea.php") echo "class='active'";?>><a class="" href="add_plazavea.php">Add Plazavea Product</a></li>
                          <li <?php if($cur_page=="add_plazavea_list.php") echo "class='active'";?>><a class="" href="add_plazavea_list.php">Add Plazavea List</a></li>
							<li <?php if($cur_page=="add_tottus.php") echo "class='active'";?>><a class="" href="add_tottus.php">Add Tottus Product</a></li>
						  <li <?php if($cur_page=="add_tottus_list.php") echo "class='active'";?>><a class="" href="add_tottus_list.php">Add Tottus List</a></li>
						<li <?php if($cur_page=="add_wong.php") echo "class='active'";?>><a class="" href="add_wong.php">Add Wong Product</a></li>
						  <li <?php if($cur_page=="add_wong_list.php") echo "class='active'";?>><a class="" href="add_wong_list.php">Add Wong List</a></li>
						<li <?php if($cur_page=="add_vivanda.php") echo "class='active'";?>><a class="" href="add_vivanda.php">Add Vivanda Product</a></li>
						  <li <?php if($cur_page=="add_vivanda_list.php") echo "class='active'";?>><a class="" href="add_vivanda_list.php">Add Vivanda List</a></li>
						
						</ul>
                  </li>
               
				  
				  <li class="sub-menu <?php if($cur_page=="View_Products.php" || $cur_page=="view_father.php" || $cur_page=="search_products.php" ) echo "active";?>">
                      <a href="javascript:;" class="">
                          <i class="icon-truck"></i>
                          <span>Product</span>
                          <span class="arrow"></span>
                      </a>
                      <ul class="sub">
                          <li <?php if($cur_page=="View_Products.php") echo "class='active'";?>><a class="" href="View_Products.php">View Products</a></li>
                          <li <?php if($cur_page=="view_father.php") echo "class='active'";?>><a class="" href="view_father.php">View Father Links</a></li>
						  <li <?php if($cur_page=="search_products.php") echo "class='active'";?>><a class="" href="search_products.php">Search Products</a></li>
                        </ul>
                  </li>
				  <?php 
				  }  ?>
                </ul>
              <!-- sidebar menu end-->
          </div>
 </aside>
     