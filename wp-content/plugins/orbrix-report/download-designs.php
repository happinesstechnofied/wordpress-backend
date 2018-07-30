<?php
  $wp_analytify = new OT_razorpay_report();
?>
<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default" style="background: white;    margin: 15px;    padding: 15px;    margin-top: 40px;border: solid;">
					<div class="panel-heading" style="border-bottom: solid 2px;    margin-bottom: 15px;">
						<h1>User Downloaded Design Reports</h1>
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div class="popup-gallery">
							<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-admin">
											<thead>
												<tr>
													<th>#</th>
													<th>Design</th>
													<th width="100px">No of Downloads</th>
													<th>Downloaded By</th>
													<!--<th>Contact Info</th>
													<th>Downloads</th>
													<th>Date</th>-->
													<!--<th>Status</th>-->
												</tr>
											</thead>
											<tbody>
											<?php
												global $wpdb;
												$results = $wpdb->get_results("SELECT *, count(product_id)as total_no FROM `wpfr_download_report` GROUP by product_id DESC");
												
												//print_r($results);

												$counter = 1;
												$total_investment = 0;
												foreach($results as $row)
												{
														?>
														<tr class="odd gradeX row_<?php echo $row->id;?>" style=" vertical-align: top;">
															<td><?php echo $counter;?></td>
															<td style="border-bottom:1px solid;">
															<?php
																
																$product_id = $row->product_id;
																	
																	$post = get_post( $product_id );
																	$image_small = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'product-thumb');
																	$product_img = $image_small[0];
																	$url = get_permalink( $product_id );
																	
																	echo "<div style='padding:5px;display:inline-block;text-align: center;' ><img src='".$product_img."' height='50px' width='50px' /><br><label> <a href='".$url."' target='_blank'>#".$product_id." ".$post->post_title.",</a></label></div>";
																	
																	$count++;
																	if($count == 4){
																		$count = 0;
																		echo "<br>";
																	}
																
																
															?>
																
															</td>
															<td style="text-align:center;"><?php echo $row->total_no;?></td>
															
															<td>
																<table width="100%" class="table table-striped table-bordered table-hover">
															<?php 
																$results_inner = $wpdb->get_results("SELECT * , count(customer_id) as customer_dnld FROM `wpfr_download_report` where product_id ='".$row->product_id."' GROUP BY customer_id order by create_date DESC" );
																
																foreach($results_inner as $row_inner)
																{
																	?>
																	
																		<tr>
																			<td width="40%"><?php
																	$user_info = get_userdata($row_inner->customer_id);
																	echo "<a href='".get_edit_user_link( $row_inner->customer_id )."' target='_blank'>#".$row_inner->customer_id." ".$user_info->user_login."</a>";
																	?></td>
																			<td width="20%"><?php echo date("d M Y",strtotime($row_inner->create_date));?></td>
																			<td><?php echo $user_info->billing_country; ?></td>
																			<td width="20%"><?php echo $user_info->billing_phone; ?></td>
																			<td><?php echo $row_inner->customer_dnld; ?></td>
																		</tr>
																	<?php
																}
															?>
																</table>
																
															</td>
														</tr>
														<?php
														$counter++;
												}
											?>
											</tbody>
										</table>
						</div>
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
			
		</script>