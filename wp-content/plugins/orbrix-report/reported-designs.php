<?php
  $wp_analytify = new OT_razorpay_report();
?>
<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default" style="background: white;    margin: 15px;    padding: 15px;    margin-top: 40px;border: solid;">
					<div class="panel-heading" style="border-bottom: solid 2px;    margin-bottom: 15px;">
						<h1>Report Designs</h1>
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div class="popup-gallery">
							<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-admin">
											<thead>
												<tr>
													<th>#</th>
													<th>Product</th>
													<th>Reason</th>
													<th>Customer</th>
													<th>Status</th>
													<th>Follow Up</th>
													<th>Reported Date</th>
												</tr>
											</thead>
											<tbody>
											<?php
												global $wpdb;
												$results = $wpdb->get_results("SELECT * FROM `wpfr_report_design` order by created_date DESC");
												
												//print_r($results);

												$counter = 1;
												$total_investment = 0;
												foreach($results as $row)
												{
													
											?>
														<tr class="odd gradeX row_<?php echo $row->id;?>">
															<td><?php echo $counter;?></td>
															<td>
															<?php
																$products = explode(",",$row->product_id);
																
																$count = 0;
																foreach($products as $product_id){
																	
																	$post = get_post( $product_id );
																	$image_small = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'product-thumb');
																	$product_img = $image_small[0];
																	$url = get_permalink( $product_id ) ;
																	
																	echo "<div style='padding:5px;display:inline-block;text-align: center;' ><img src='".$product_img."' height='50px' width='50px' /><br><label> <a href='".$url."' target='_blank'>#".$product_id." ".$post->post_title.",</a></label></div>";
																	
																	$count++;
																	if($count == 4){
																		$count = 0;
																		echo "<br>";
																	}
																}
																
															?>
															
															</td>
															<td><?php echo $row->reason;?></td>
															<td><br />
															<?php
																$user_info = get_userdata($row->user_id);
																echo "<a href='".get_edit_user_link( $row->user_id )."' target='_blank'>#".$row->user_id." ".$user_info->user_login."</a>";
																?></td>
																<td style="color:transparent;"><?php echo $row->status;?><input type="checkbox" name="status" id="status" value="<?php echo $row->status;?>" data-id="<?php echo $row->id;?>" class="slider-switch slider-switch_<?php echo $row->id;?>" style="" <?php echo (($row->status=="1") ? 'checked' : ''); ?>></input></td>
															<td><div class="follow_<?php echo $row->id;?>" ><?php echo $row->followup;?></div> &nbsp;&nbsp;<i class="fa fa-pencil-square-o text-primary fa-2x" data-toggle="modal" data-target="#modalForm" data-followup="<?php echo $row->followup;?>" data-rowid="<?php echo $row->id;?>" ></i>&nbsp;&nbsp;<i class="fa fa-trash-o text-danger fa-2x" onclick="delete_slider('<?php echo $row->id;?>');" ></i></td>
															<td><?php echo date("d M Y",strtotime($row->created_date));?></td>
														</tr>
														<?php
														$counter++;
												}
											?>
											</tbody>
										</table>
						</div>
						
						<!-- Modal -->
						
						<div class="modal fade" id="modalForm" role="dialog">
							<div class="modal-dialog">
								<div class="modal-content">
									<!-- Modal Header -->
									
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">
											<span aria-hidden="true">&times;</span>
											<span class="sr-only">Close</span>
										</button>
										<h4 class="modal-title" id="myModalLabel">Follow Up Detail</h4>
									</div>
									
									<!-- Modal Body -->
									
									<div class="modal-body">
										<p class="statusMsg"></p>
										<form role="form">
											<!--<div class="form-group">
												<label for="inputName">Name</label>
												<input type="text" class="form-control" id="inputName" placeholder="Enter your name"/>
											</div>
											<div class="form-group">
												<label for="inputEmail">Email</label>
												<input type="email" class="form-control" id="inputEmail" placeholder="Enter your email"/>
											</div>-->
											<div class="form-group">
												<label for="inputMessage">Follow Up</label>
												<textarea class="form-control" id="inputMessage" placeholder="Enter your follow up details"></textarea>
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
			
			
			jQuery('#modalForm').on('show.bs.modal', function (event) {
			  var button = $(event.relatedTarget); // Button that triggered the modal
			  var followup = button.data('followup');
			  var rowid = button.data('rowid');			  // Extract info from data-* attributes
			  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
			  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
			  var modal = $(this);
			  //modal.find('.modal-title').text('New message to ' + recipient)
			  modal.find('.modal-body textarea#inputMessage').val(followup);
			  
			  modal.find('.modal-footer #btn_submit').attr('onclick','submitForm(\''+rowid+'\')');
			  
		});
		
		
		
		function submitForm(row_id){
			var followup = $('#inputMessage').val();
			
			jQuery.ajax({
				type:'POST',
				url:'<?php echo plugins_url( 'ajax_plugin.php', __FILE__ );?>',
				data:'action=report_design_followup&row_id='+row_id+'&followup='+followup,
				beforeSend: function () {
					$('.submitBtn').attr("disabled","disabled");
					$('.modal-body').css('opacity', '.5');
				},
				success:function(msg){
					if(msg == 'success'){
						$('.follow_'+row_id).html(followup);
						$('#inputMessage').val('');
						$('.statusMsg').html('<span style="color:green;">Thanks for submit follow up.</p>');
					}else{
						$('.statusMsg').html('<span style="color:red;">Some problem occurred, please try again.</span>');
					}
					$('.submitBtn').removeAttr("disabled");
					$('.modal-body').css('opacity', '');
				}
			});
		}
			
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
					action: 'change_report_design_status',
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
		
		
		function delete_slider(id){
			var r = confirm("Are you sure want to remove Reported Design Report?");
			if (r == true) {
				
				var data = {
					action: 'delete_reported_design',
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
			
		</script>