<?php
  $wp_analytify = new OT_razorpay_report();
?>
<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default" style="background: white;    margin: 15px;    padding: 15px;    margin-top: 40px;border: solid;">
					<div class="panel-heading" style="border-bottom: solid 2px;    margin-bottom: 15px;">
						<h1>User Order Reports</h1>
					</div>
					
					
					<?php
					$users = get_users();
					
					$order_history = array();
					
					foreach($users as $user){
						$user_info = get_userdata($user->ID);
						
						// echo $user_info->first_name." ".$user_info->last_name."<br>";						
						// echo "<a href='".get_edit_user_link( $user->ID )."' target='_blank'>".$user_info->user_login."</a><br>";						
						// //echo $user_info->billing_country."<br>";
						// echo $user_info->billing_phone."<br>";
						//echo $user_info->billing_country."<br>";
						
						$row_array = array();
						$row_array['name'] = $user_info->first_name." ".$user_info->last_name;
						$row_array['email'] = "<a href='".get_edit_user_link( $user->ID )."' target='_blank'>".$user_info->user_login."</a>";
						$row_array['phone'] = $user_info->billing_phone;
						
						
						$customer_orders = get_posts( array(
							'numberposts' => -1,
							'meta_key'    => '_customer_user',
							'meta_value'  => $user->ID,
							'post_type'   => wc_get_order_types(),
							'post_status' => array_keys( wc_get_order_statuses() ),
						) );
						
						$row_array['orders'] = array();
						$total = 0;
						foreach ( $customer_orders as $customer_order ) {
							$order = wc_get_order( $customer_order );
							
							$order_inner = array();
							
							
							
							
							$order_inner['order_number'] = '<a href="'.get_edit_post_link($order->get_order_number()).'" target="_blank">'.$order->get_order_number().'</a>';
							$order_inner['order_date'] = $order->order_date;
							$order_inner['order_total'] = count($order->get_items());
							
							$row_array['orders'][] = $order_inner;
							
							$total++;
						}
						
						if($total > 0){
							$order_history[] = $row_array;
						}
						//print_r($row_array);
						
					}
					
					
					//print_r($order_history);
					
					?>
					
					
					
					
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div class="popup-gallery">
							<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-admin">
											<thead>
												<tr>
													<th>#</th>
													<th>Name</th>
													<th>Email</th>
													<th>Phone</th>
													<th>Order Number</th>
													<th>Date</th>
													<th>Total Items</th>
													<!--<th>Status</th>-->
												</tr>
											</thead>
											<tbody>
											<?php
												$counter = 1;
												foreach($order_history as $row)
												{
														?>
														<tr class="odd gradeX row_<?php echo $row->id;?>" style=" vertical-align: top;">
															<td><?php echo $counter;?></td>
															<td><?php echo $row['name'];?></td>
															<td><?php echo $row['email'];?></td>
															<td><?php echo $row['phone'];?></td>
															
															<td><?php 
															foreach($row['orders'] as $order_data){
																echo $order_data['order_number']."<br>";
															}
															?></td>
															<td><?php 
															foreach($row['orders'] as $order_data){
																echo date("d M Y",strtotime($order_data['order_date'])) ."<br>";
															}
															?></td>
															<td><?php 
															foreach($row['orders'] as $order_data){
																echo $order_data['order_total']."<br>";
															}
															?></td>
															
															
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