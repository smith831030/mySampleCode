
				<?php echo $this->Session->flash(); ?>
				<div class="row">
					<h2>Company</h2>
					<div class="col-md-12 form-group table-responsive checkbox" id="product_list">
						<button type="button" class="btn btn-default btn_add_new_item" aria-label="Add new item" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Company/AddNewCompany';">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add new company
						</button> 
					</div>
					
					<div id="div_order_list" class="col-md-12 form-group table-responsive checkbox">
						<form name="memberForm" id="memberForm" action="<?php echo $mainPageUrl;?>/" method="post">
							<fieldset>
								<table class="table table-hover ">
									<thead>
										<tr>
											<th>Logo</th>
											<th>Company</th>
											<th>ABN</th>
											<th>Address</th>
											<th>Contact</th>
											<th>Email</th>
											<th></th>
										</tr>
									</thead>
									
									<tbody>
										<?php
										foreach($companies as $list):
										?>
										<tr>
											<td><img src="<?php echo $mainPageUrl;?>/upload/<?php echo $list['IvInformation']['if_logo'];?>" style="max-height:40px;" /></td>
											<td><?php echo $list['IvInformation']['if_company'];?></td>
											<td><?php echo $list['IvInformation']['if_abn'];?></td>
											<td><?php echo $list['IvInformation']['if_address'];?></td>
											<td><?php echo $list['IvInformation']['if_contact'];?></td>
											<td><?php echo $list['IvInformation']['if_email'];?></td>
											<td>
												<button type="button" class="btn btn-default btn-xs" title="modify" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Company/Modify/<?php echo $list['IvInformation']['if_idx'];?>';">
													<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
												</button>
												<button type="button" class="btn btn-default btn-xs" title="delete" onclick="delCompany(<?php echo $list['IvInformation']['if_idx'];?>);">
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
			
