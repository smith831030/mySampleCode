
				<?php echo $this->Session->flash(); ?>
				<div class="row">
					<h2>Bank</h2>
					<div class="col-md-12 form-group table-responsive checkbox" id="product_list">
						<button type="button" class="btn btn-default" aria-label="Add new bank" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Bank/AddNew';">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add new bank
						</button> 
					</div>
					
					<div id="div_order_list" class="col-md-12 form-group table-responsive checkbox">
						<form name="agentForm" id="agentForm" action="<?php echo $mainPageUrl;?>/" method="post">
							<fieldset>
								<table class="table table-hover ">
									<thead>
										<tr>
											<th>Bank name</th>
											<th>Account no</th>
											<th>Account name</th>
											<th>BSB</th>
											<th></th>
										</tr>
									</thead>
									
									<tbody>
										<?php
										foreach($banks as $list):
										?>
										<tr>
											<td><?php echo $list['ItBank']['b_name'];?></td>
											<td><?php echo $list['ItBank']['b_acc_no'];?></td>
											<td><?php echo $list['ItBank']['b_acc_name'];?></td>
											<td><?php echo $list['ItBank']['b_bsb'];?></td>
											<td>
												<button type="button" class="btn btn-default btn-xs" title="modify" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Bank/Modify/<?php echo $list['ItBank']['b_idx'];?>';">
													<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
												</button>
												<button type="button" class="btn btn-default btn-xs" title="delete" onclick="delBank(<?php echo $list['ItBank']['b_idx'];?>);">
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
			
