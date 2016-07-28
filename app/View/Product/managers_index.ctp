
				<?php echo $this->Session->flash(); ?>
				<div class="row">
					<h2>Product</h2>
					<div class="col-md-12 form-group table-responsive checkbox" id="product_list">
						<button type="button" class="btn btn-default" aria-label="Add new product" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Product/AddNew';">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add new product
						</button> 
					</div>
					
					<div id="div_order_list" class="col-md-12 form-group table-responsive checkbox">
						<form name="agentForm" id="agentForm" action="<?php echo $mainPageUrl;?>/" method="post">
							<fieldset>
								<table class="table table-hover ">
									<thead>
										<tr>
											<th>Category</th>
											<th>Product name</th>
											<th>Price</th>
											<th>Type</th>
											<th></th>
										</tr>
									</thead>
									
									<tbody>
										<?php
										foreach($products as $list):
										?>
										<tr>
											<td><?php echo $list['IvCategory']['cate_title'];?></td>
											<td><?php echo $list['IvProduct']['pro_name'];?></td>
											<td>$<?php echo number_format($list['IvProduct']['pro_price'], 2);?></td>
											<td>
											<?php 
												if($list['IvProduct']['pro_type']==1)
													echo 'Ea';
												else
													echo 'Per Week';
											?>
											</td>
											<td>
												<button type="button" class="btn btn-default btn-xs" title="modify" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Product/Modify/<?php echo $list['IvProduct']['pro_idx'];?>';">
													<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
												</button>
												<button type="button" class="btn btn-default btn-xs" title="delete" onclick="delProduct(<?php echo $list['IvProduct']['pro_idx'];?>);">
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
			
