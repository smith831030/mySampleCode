
				
				<div class="row">
					<?php echo $this->Session->flash(); ?>
					<h2>Customer</h2>
					<div class="col-md-12 form-group table-responsive checkbox" id="product_list">
						<button type="button" class="btn btn-default" aria-label="Add new customer" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Customer/AddNew';">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add new customer
						</button> 
					</div>
					
					<div id="div_order_list" class="col-md-12 form-group table-responsive checkbox">
						<form name="agentForm" id="agentForm" action="<?php echo $mainPageUrl;?>/" method="post">
							<fieldset>
								<table class="table table-hover ">
									<thead>
										<tr>
											<th>Company</th>
											<th>Description</th>
											<th>Address</th>
											<th>Contact</th>
											<th>Attention</th>
											<th></th>
										</tr>
									</thead>
									
									<tbody>
										<?php
										foreach($customers as $list):
										?>
										<tr>
											<td><?php echo $list['IvCustomer']['c_company'];?></td>
											<td><?php echo $list['IvCustomer']['c_description'];?></td>
											<td><?php echo $list['IvCustomer']['c_address'];?></td>
											<td><?php echo $list['IvCustomer']['c_contact'];?></td>
											<td><?php echo $list['IvCustomer']['c_attention'];?></td>
											<td>
												<button type="button" class="btn btn-default btn-xs" title="modify" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Customer/Modify/<?php echo $list['IvCustomer']['c_idx'];?>';">
													<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
												</button>
												<button type="button" class="btn btn-default btn-xs" title="delete" onclick="delCustomer(<?php echo $list['IvCustomer']['c_idx'];?>);">
													<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
												</button>
											</td>
										</tr>
										<?php
										endforeach;
										unset($list);
										?>
									</tbody>
								</table>
							</fieldset>
						</form>
					</div>
				</div>
				
			<?php //echo $this->element('sql_dump'); ?>
			
