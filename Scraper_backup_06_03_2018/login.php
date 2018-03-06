<?php 

session_start();
include("includes/Library.php");
include("includes/config.php");
include("includes/DatabaseConnection.php");

if(isset($_SESSION["user_id"]) && $_SESSION["user_id"]!='')
{
	header("Location:View_Products.php");
}


//login
if(
isset($_POST["user_name"]) && $_POST["user_name"]!='' && 
isset($_POST["password"]) && $_POST["password"]!=''
)
{
	$sql="SELECT * FROM User WHERE user_name='".$_POST["user_name"]."' AND password='".md5($_POST["password"])."'";
	
	$rs=mysqli_query($mysqli,$sql) or die_u(mysqli_error($mysqli));
	if(mysqli_num_rows($rs)>0)
	{
		//user logged in save info in the session
		$res=mysqli_fetch_array($rs);
		$_SESSION["user_id"]=$res["user_id"];
		$_SESSION["user_type"]=$res["user_type"];
		$_SESSION["user_name"]=$res["user_name"];
		$_SESSION["email"]=$res["email"];
	    header("Location:index.php");
	}
	else
	{
		die_u("Username or password is wrong!");
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
    <link rel="shortcut icon" href="img/favicon.html">

    <title>Scraper Admin Panel Login</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

	 <script src="js/jquery.min.js"></script>
	<script src="js/jquery.form-validator.min.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

  <body class="login-body">

    <div class="container">

      <form class="form-signin" id="login_form" action="" method="POST">
        <h2 class="form-signin-heading">sign in now</h2>
        <div class="login-wrap">
            <div><input type="text" data-validation="required" class="form-control" name="user_name" placeholder="Username" autofocus></div>
           <div>   <input type="password" data-validation="required" class="form-control" name="password" placeholder="Password"></div>
           <!-- <label class="checkbox">
                <span class="pull-right"> <a href="#"> Forgot Password?</a></span>
            </label>-->
            <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>
         
           <!-- <div class="registration">
                Don't have an account yet?
                <a class="" href="registration.html">
                    Create an account
                </a>
            </div>-->

        </div>

      </form>

    </div>

 <!--##modal for error message##--> 
 <div class="modal fade" id="myModal_error" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                              <h4 class="modal-title"><?php if(isset($error_title)) echo $error_title; else echo "Oops, this is an error!";?></h4>
                                          </div>
                                          <div class="modal-body">

                                              <?php 
											  if(isset($error_msg)) echo $error_msg;
											  ?>

                                          </div>
                                          <div class="modal-footer">
                                              <button id="close_error_modal" class="btn btn-danger" type="button"> Ok</button>
                                          </div>
										  <script>
										  $(function(){
											  $("#close_error_modal").on("click",function(){
												  $("#myModal_error").modal("hide");
											  });
										  });
										  </script>
                                      </div>
                                  </div>
                              </div>

	
  </body>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.scrollTo.min.js"></script>
    <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/gritter/js/jquery.gritter.js"></script>
    <script src="js/respond.min.js" ></script>

    <!--common script for all pages-->
    <script src="js/common-scripts.js"></script>

<script>
$.validate({
  form : '#login_form',
   modules : 'security',
   onSuccess: function(){
   }
});
</script>
<?php 
if(isset($error) && $error==1)
{
?>
<script>
$(function(){
	$('#myModal_error').modal('show');
});
</script>
<?php 
}
?>
</html>
