<?php
  $wp_analytify = new OT_razorpay_report();
?>
<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default" style="background: white;    margin: 15px;    padding: 15px;    margin-top: 40px;border: solid;">
					<div class="panel-heading" style="border-bottom: solid 2px;    margin-bottom: 15px;">
						<h1 style="display: inline-block;">Apprtments Lists</h1>
						<div style="float:right;margin-top: 12px;">
							<button class="btn btn-success btn-lg" data-toggle="modal" data-target="#modalForm">
								Add Apprtment
							</button>
						</div>
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div class="popup-gallery">
							<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-admin">
											<thead>
												<tr>
													<th>#</th>
													<th>App Name</th>
													<th>Link</th>
													<th>Status</th>
													<th>Action</th>
													<th>Created Date</th>
												</tr>
											</thead>
											<tbody>
											<?php
												global $wpdb;
												$results = $wpdb->get_results("SELECT * FROM `wpfr_recommended_app` order by created_date DESC");
												
												//print_r($results);

												$counter = 1;
												$total_investment = 0;
												foreach($results as $row)
												{
													
														?>
														<tr class="odd gradeX row_<?php echo $row->id;?>">
															<td><?php echo $counter;?></td>
															<td><?php echo $row->name;?></td>
															<td><div style='padding:5px;display:inline-block;text-align: center;    width: 100%;'  ><img src='<?php echo $row->image;?>' height='50px' width='50px' /><br><label> <a href='<?php echo $row->link;?>' target='_blank'><?php echo $row->link;?></a></label></div></td>
															<td style="color:transparent;"><?php echo $row->status;?><input type="checkbox" name="status" id="status" value="<?php echo $row->status;?>" data-id="<?php echo $row->id;?>" class="slider-switch slider-switch_<?php echo $row->id;?>" style="" <?php echo (($row->status=="1") ? 'checked' : ''); ?>></input></td>
															<td><i class="fa fa-pencil-square-o text-primary fa-2x" data-toggle="modal" data-target="#modalFormEdit" data-app_name="<?php echo $row->name;?>" data-app_image="<?php echo $row->image;?>" data-app_link="<?php echo $row->link;?>" data-status="<?php echo $row->status;?>" data-rowid="<?php echo $row->id;?>" ></i>&nbsp;&nbsp;<i class="fa fa-trash-o text-danger fa-2x" onclick="delete_slider('<?php echo $row->id;?>');" ></i></td>
															<td><?php echo date("d M Y",strtotime($row->created_date));?></td>
														</tr>
														<?php
														$counter++;
												}
											?>
											</tbody>
										</table>
						</div>
						
								<?php 
								wp_enqueue_media();
								?>
						
						<div class="modal fade" id="modalForm" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
								
								
									<!-- Modal Header -->
									
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">
											<span aria-hidden="true">&times;</span>
											<span class="sr-only">Close</span>
										</button>
										<h4 class="modal-title" id="myModalLabel">Add new apprtment</h4>
									</div>
									
									<!-- Modal Body -->
									
									<div class="modal-body">
										<p class="statusMsg"></p>
										<form role="form" enctype="multipart/form-data">
											<div class="form-group">
												<label for="apprtment_name">Apprtment Name</label>
												<input type="text" class="form-control" id="apprtment_name" placeholder="Enter your name"/>
											</div>
											
											<div class="form-group">
												<label for="countryId">Country</label>
												<select name="country" class="form-control countries" id="countryId" required="required">
													<option value="">Select Country</option>
												</select>
											</div>
											
											<div class="form-group">
												<label for="stateId">State</label>
												<select name="state" class="form-control states" id="stateId" required="required">
													<option value="">Select State</option>
												</select>
											</div>
											
											<div class="form-group">
												<label for="cityId">City</label>
												<select name="city" class="form-control cities" id="cityId" required="required">
													<option value="">Select City</option>
												</select>
											</div>
											
										
											<div class="form-group">
												<label for="upload_image_button">Choose App Image (Image should be 90x90 for proper display in all app devices)</label><br>
												<!--<input type="file" class="form-control" id="inputEmail" placeholder="Enter your email"/>
												<label for="mobile_numbers" class="col-sm-2 control-label">Image</label>-->


												<div class='image-preview-wrapper'>
													<!--<img id='image-preview' src='' width='100' height='100' style='max-height: 100px; width: 100px;'>-->
												</div>
												<input id="upload_image_button" type="button" class="button" value="Upload image" />
												<input type='hidden' name='image_attachment_id' id='image_attachment_id' value=''>

								
											</div>
											<div class="form-group">
												<label for="app_link">Application Link</label>
												<textarea class="form-control" id="app_link" placeholder="Enter your follow up details"></textarea>
											</div>
											
											
											
										</form>
									</div>
									
									<!-- Modal Footer -->
									
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<button type="button" class="btn btn-primary submitBtn" id="btn_submit" onclick="submitContactForm()">SUBMIT</button>
									</div>
								</div>
							</div>
						</div>
						
						
						<div class="modal fade" id="modalFormEdit" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
								
								<?php 
								wp_enqueue_media();
								?>
									<!-- Modal Header -->
									
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">
											<span aria-hidden="true">&times;</span>
											<span class="sr-only">Close</span>
										</button>
										<h4 class="modal-title" id="myModalLabel">Update recommended app</h4>
									</div>
									
									<!-- Modal Body -->
									
									<div class="modal-body">
										<p class="statusMsg"></p>
										<form role="form" enctype="multipart/form-data">
											<div class="form-group">
												<label for="app_name">App Name</label>
												<input type="text" class="form-control" id="app_name" placeholder="Enter your name"/>
											</div>
											<div class="form-group">
												<label for="upload_image_button2">Choose App Image (Image should be 90x90 for proper display in all app devices)</label><br>
												<!--<input type="file" class="form-control" id="inputEmail" placeholder="Enter your email"/>
												<label for="mobile_numbers" class="col-sm-2 control-label">Image</label>-->


												<div class='image-preview-wrapper2'>
													<!--<img id='image-preview' src='' width='100' height='100' style='max-height: 100px; width: 100px;'>-->
												</div>
												<input id="upload_image_button2" type="button" class="button" value="Upload image" />
												<input type='hidden' name='image_attachment_id2' id='image_attachment_id2' value=''>

								
											</div>
											<div class="form-group">
												<label for="app_link">Application Link</label>
												<textarea class="form-control" id="app_link" placeholder="Enter your follow up details"></textarea>
											</div>
											
											
											
										</form>
									</div>
									
									<!-- Modal Footer -->
									
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<button type="button" class="btn btn-primary submitBtn" id="btn_submit" onclick="submitContactForm()">SUBMIT</button>
									</div>
								</div>
							</div>
						</div>
						
						<!-- end popupmodel -->
						
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->
		<script>
		
			jQuery(document).ready(function() {
				jQuery('#dataTables-admin').DataTable({
					responsive: true,
					'searching': true,
					dom: 'Bf<br>lrtip',
					buttons: [{extend: 'colvis', text: 'Show'},
						{ extend : 'collection',
						text:'Export',
						buttons: [{extend: 'copy', className:'', title: 'Universal-report'},
							{extend: 'excel', className:'', title: 'Universal-report'},
							{extend: 'csv', className:'', title: 'Universal-report'},
							{extend: 'pdf', className:'', title: 'Universal-report'},
							{extend: 'print', className:'', title: 'Universal-report'}]
							}
						],
						'lengthMenu': [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ]
				});
			});
			
			jQuery('#modalFormEdit').on('show.bs.modal', function (event) {
				  var button = $(event.relatedTarget); // Button that triggered the modal
				  
				  var app_name = button.data('app_name');
				  var app_image = button.data('app_image');
				  var app_link = button.data('app_link');
				  //var status = button.data('status');
				  var rowid = button.data('rowid');			  // Extract info from data-* attributes
				  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
				  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
				  var modal = $(this);
				  //modal.find('.modal-title').text('New message to ' + recipient)
				  modal.find('.modal-body #app_name').val(app_name);
				  
				  
				  
				  modal.find('.modal-body .image-preview-wrapper2').html('<img id="image-preview" src="'+app_image+'" width="auto" height="100" style="max-height: 100px;">');
				  modal.find('.modal-body #app_link').val(app_link);
				  //modal.find('.modal-body textarea#inputMessage').val(followup);
				  
				  modal.find('.modal-footer #btn_submit').attr('onclick','submitForm(\''+rowid+'\')');
				  
			});
			
			
			function submitForm(row_id){
				var app_name = $('#modalFormEdit #app_name').val();
				var app_image = $(" #modalFormEdit #image-preview").attr("src");
				var app_link = $('#modalFormEdit #app_link').val();
				//var status = '0';
				
				
				jQuery.ajax({
					type:'POST',
					url:'<?php echo plugins_url( 'ajax_plugin.php', __FILE__ );?>',
					data:'action=update_recommended_app&row_id='+row_id+'&app_name='+app_name+'&app_image='+app_image+'&app_link='+app_link,
					beforeSend: function () {
						$('.submitBtn').attr("disabled","disabled");
						$('.modal-body').css('opacity', '.5');
					},
					success:function(msg){
						if(msg == 'success'){
							
							$('#inputMessage').val('');
							$('.statusMsg').html('<span style="color:green;">Thanks for new recomended app.</p>');
							
							setTimeout(function(){
									window.location.href=window.location.href;
								}, 3000);
							
						}else{
							$('.statusMsg').html('<span style="color:red;">Some problem occurred, please try again.</span>');
						}
						$('.submitBtn').removeAttr("disabled");
						$('.modal-body').css('opacity', '');
					}
				});
			}
			
			function submitContactForm(){
				var app_name = $('#modalForm #app_name').val();
				var app_image = $("#modalForm #image-preview").attr("src");
				var app_link = $('#modalForm #app_link').val();
				var status = '0';
				
				
				jQuery.ajax({
					type:'POST',
					url:'<?php echo plugins_url( 'ajax_plugin.php', __FILE__ );?>',
					data:'action=add_recommended_app&app_name='+app_name+'&app_image='+app_image+'&app_link='+app_link+'&status='+status,
					beforeSend: function () {
						$('.submitBtn').attr("disabled","disabled");
						$('.modal-body').css('opacity', '.5');
					},
					success:function(msg){
						if(msg == 'success'){
							
							$('#inputMessage').val('');
							$('.statusMsg').html('<span style="color:green;">Thanks for new recomended app.</p>');
							setTimeout(function(){
									window.location.href=window.location.href;
								}, 3000);
						}else{
							$('.statusMsg').html('<span style="color:red;">Some problem occurred, please try again.</span>');
						}
						$('.submitBtn').removeAttr("disabled");
						$('.modal-body').css('opacity', '');
					}
				});
			}
			
			
			
			var elems = document.querySelectorAll('.slider-switch');

			for (var i = 0; i < elems.length; i++) {
				var changeCheckbox = new Switchery(elems[i]);
				  
				elems[i].onchange = function(e) {
					
					
					var data_id = $(this).attr("data-id");
					
					
					if ($(this).is(':checked')) {
						user_status = '1';
					}else{
						user_status = '0';
					}
					
					// $.ajax({
						// url: '<?php echo SITE_URL;?>ajax_data.php',
						// type: 'POST',
						// data: {'action':'change_user_status','id' : data_id,'user_status' : user_status},
						// error: function() {
							// alert("Failed to update please try again...");
						// },
						// success: function(data) {
							// if(data=="fail"){
								// alert("Failed to update please try again...");
							// }
						// }
					// });
					
					//ajax call 
					var data = {
						action: 'change_recom_app_status',
						id: data_id,
						user_status: user_status
					};
					jQuery.post('<?php echo plugins_url( 'ajax_plugin.php', __FILE__ );?>', data, function(response) {
						if(response=="fail"){
							alert("Failed to update please try again...");
						}else{
							
						}
					});
					
				}
			}
			
			
			
			
			
			// Uploading files
			var file_frame;
			//var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			//var set_to_post_id = 0; // Set this
			jQuery('#upload_image_button').on('click', function( event ){
				event.preventDefault();
				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					// Set the post ID to what we want
					//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					// Open frame
					file_frame.open();
					return;
				} else {
					// Set the wp.media post id so the uploader grabs the ID we want when initialised
					//wp.media.model.settings.post.id = set_to_post_id;
				}
				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false	,// Set to true to allow multiple files to be selected
					library : {
                        type : 'image',
                    }
				});
				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = file_frame.state().get('selection').first().toJSON();
					// Do something with attachment.id and/or attachment.url here
					//$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
					jQuery( '#image_attachment_id' ).val( attachment.id );
					
					jQuery(".image-preview-wrapper").html('<img id="image-preview" src="'+attachment.url+'" width="auto" height="100" style="max-height: 100px;">');
					
					
					
					// Restore the main post ID
					//wp.media.model.settings.post.id = wp_media_post_id;
				});
					// Finally, open the modal
					file_frame.open();
			});
			
			
			jQuery('#upload_image_button2').on('click', function( event ){
				event.preventDefault();
				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					// Set the post ID to what we want
					//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					// Open frame
					file_frame.open();
					return;
				} else {
					// Set the wp.media post id so the uploader grabs the ID we want when initialised
					//wp.media.model.settings.post.id = set_to_post_id;
				}
				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false	,// Set to true to allow multiple files to be selected
					library : {
                        type : 'image',
                    }
				});
				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = file_frame.state().get('selection').first().toJSON();
					// Do something with attachment.id and/or attachment.url here
					//$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
					jQuery( '#image_attachment_id2' ).val( attachment.id );
					
					jQuery(".image-preview-wrapper2").html('<img id="image-preview" src="'+attachment.url+'" width="auto" height="100" style="max-height: 100px;">');
					
					
					
					// Restore the main post ID
					//wp.media.model.settings.post.id = wp_media_post_id;
				});
					// Finally, open the modal
					file_frame.open();
			});
			
			
			// Restore the main ID when the add media button is pressed
			jQuery( 'a.add_media' ).on( 'click', function() {
				//wp.media.model.settings.post.id = wp_media_post_id;
			});
			

			function delete_slider(id){
				var r = confirm("Are you sure want to remove Recommended App Report?");
				if (r == true) {
					
					var data = {
						action: 'delete_recomended_app',
						id: id
					};
					jQuery.post('<?php echo plugins_url( 'ajax_plugin.php', __FILE__ );?>', data, function(response) {
						if(response=="success"){
							jQuery(".row_"+id).remove();
						}else{
							alert("Failed to delete report please try again...");
						}
					});
					
				} else {
					
				}
			}
			
			
			
			
			
			
			
			
			
			/* custom code for country state city dropdowns */
	function ajaxCall() {
        this.send = function(data, url, method, success, type) {
          type = type||'json';
          var successRes = function(data) {
              success(data);
          };

          var errorRes = function(e) {
              console.log(e);
              alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
          };
            jQuery.ajax({
                url: url,
                type: method,
                data: data,
                success: successRes,
                error: errorRes,
                dataType: type,
                timeout: 60000
            });

          }

        }

function locationInfo() {
    var rootUrl = "api.php";
    var call = new ajaxCall();
    this.getCities = function(id) {
        jQuery(".cities option:gt(0)").remove();
		
		var url = '<?php echo plugins_url( 'location.php', __FILE__ );?>';
        var method = "post";
        var data = {action: 'getCities',stateId: id};
        jQuery('.cities').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            jQuery('.cities').find("option:eq(0)").html("Select City");
            if(data.tp == 1){
                jQuery.each(data['result'], function(key, val) {
                    var option = jQuery('<option />');
                    option.attr('value', key).text(val);
                    jQuery('.cities').append(option);
                });
                jQuery(".cities").prop("disabled",false);
            }
            else{
                 alert(data.msg);
            }
        });
    };

    this.getStates = function(id) {
        jQuery(".states option:gt(0)").remove(); 
        jQuery(".cities option:gt(0)").remove(); 
        var method = "post";
        var data = {};
		var url = '<?php echo plugins_url( 'location.php', __FILE__ );?>';
        var method = "post";
        var data = {action: 'getStates',countryId: id};
		
        jQuery('.states').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            jQuery('.states').find("option:eq(0)").html("Select State");
            if(data.tp == 1){
                jQuery.each(data['result'], function(key, val) {
                    var option = jQuery('<option />');
                    option.attr('value', key).text(val);
                    jQuery('.states').append(option);
                });
                jQuery(".states").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };

    this.getCountries = function() {
		
        var url = '<?php echo plugins_url( 'location.php', __FILE__ );?>';
        var method = "post";
        var data = {action: 'getCountries'};
        jQuery('.countries').find("option:eq(0)").html("Please wait..");
        call.send(data, url, method, function(data) {
            jQuery('.countries').find("option:eq(0)").html("Select Country");
            console.log(data);
            if(data.tp == 1){
                jQuery.each(data['result'], function(key, val) {
                    var option = jQuery('<option />');
                    option.attr('value', key).text(val);
                    jQuery('.countries').append(option);
                });
                jQuery(".countries").prop("disabled",false);
            }
            else{
                alert(data.msg);
            }
        }); 
    };
}

jQuery(function() {
var loc = new locationInfo();
loc.getCountries();
 jQuery(".countries").on("change", function(ev) {
        var countryId = jQuery(this).val();
        if(countryId != ''){
        loc.getStates(countryId);
        }
        else{
            jQuery(".states option:gt(0)").remove();
        }
    });
 jQuery(".states").on("change", function(ev) {
        var stateId = jQuery(this).val();
        if(stateId != ''){
        loc.getCities(stateId);
        }
        else{
            jQuery(".cities option:gt(0)").remove();
        }
    });
});

		</script>