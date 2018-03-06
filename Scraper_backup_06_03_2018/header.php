 <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
	 <link href="assets/morris.js-0.4.3/morris.css" rel="stylesheet" />
	 <link href="assets/advanced-datatable/media/css/demo_page.css" rel="stylesheet" />
    <link href="assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet" media="screen" />
   <link href="css/jqpagination.css" rel="stylesheet">


<!-- Include Required Prerequisites -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="js/jquery.jqpagination.min.js"></script>
<!--<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/latest/css/bootstrap.css" />-->
 
<!-- Include Date Range Picker -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
	
	<!--<script src="js/jquery.min.js"></script>-->
	<script src="js/jquery.form-validator.min.js"></script>
	<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->

	
	<!--<link rel="stylesheet" href="css/tinycolorpicker.css" type="text/css" media="screen"/>
	<script src="js/jquery.tinycolorpicker.js"></script>-->
	
	<!-- js placed at the end of the document so the pages load faster -->
    <!--<script src="js/jquery.js"></script>-->
  </head>

  <body>

  <section id="container" class="">
      <!--header start-->
      <header class="header white-bg">
          <div class="sidebar-toggle-box">
              <div data-original-title="Toggle Navigation" data-placement="right" class="icon-reorder tooltips"></div>
          </div>
          <!--logo start-->
          <a href="index.php" class="logo" >SCRAPER PROJECT</a>
          <!--logo end-->
		         
		  <div class="top-nav ">
              <ul class="nav pull-right top-menu">
                 <!-- <li>
                      <input type="text" class="form-control search" placeholder="Search">
                  </li>-->
                  <!-- user login dropdown start-->
                  <li class="dropdown">
                      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                          <span class="username"><?php echo $_SESSION["user_name"];?></span>
                          <b class="caret"></b>
                      </a>
                      <ul style="width:500px !important;" class="dropdown-menu extended logout">
                          <div class="log-arrow-up"></div>
                         <!-- <li><a href="profile.php"><i class=" icon-user"></i>Profile</a></li>-->
						 
                          <li><a href="#"><i class="icon-suitcase"></i> <?php if(isset($_SESSION["user_type"])) echo $_SESSION['user_type'];?></a></li>
						  <li><a href="#"><i class="icon-envelope"></i> <?php if(isset($_SESSION["email"])) echo $_SESSION["email"];?></a></li>
                          <li><a href="logout.php"><i class="icon-off"></i> Log Out</a></li>
						  
                      </ul>
                  </li>
                  <!-- user login dropdown end -->
              </ul>
          </div>
      </header>
	  