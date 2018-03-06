<!-- js placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.scrollTo.min.js"></script>
    <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="js/respond.min.js" ></script>
    <script type="text/javascript" language="javascript" src="assets/advanced-datatable/media/js/jquery.dataTables.js"></script>
	<script src="js/jquery.stepy.js"></script>
    <!--common script for all pages-->
    <script src="js/common-scripts.js"></script>
	
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
	
		 <!--##modal for fancy message##--> 
 <div class="modal fade" id="myModal_fancy" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                              <h4 class="modal-title"><?php if(isset($fancy_message_title)) echo $fancy_message_title; else echo "Message!";?></h4>
                                          </div>
                                          <div class="modal-body">

                                              <?php 
											 if(isset($fancy_message_text)) echo $fancy_message_text;
											  ?>

                                          </div>
                                          <div class="modal-footer">
                                              <button id="close_fancy_modal" class="btn btn-danger" type="button"> Ok</button>
                                          </div>
										  <script>
										  $(function(){
											  $("#close_fancy_modal").on("click",function(){
												  $("#myModal_fancy").modal("hide");
											  });
										  });
										  </script>
                                      </div>
                                  </div>
                              </div>
	
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

if(isset($fancy_message) && $fancy_message==1)
{
?>
<script>
$(function(){
	$('#myModal_fancy').modal('show');
});
</script>
<?php 
}
?>