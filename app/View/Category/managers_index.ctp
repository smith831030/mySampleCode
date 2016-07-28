
				<?php echo $this->Session->flash(); ?>
				<div class="row">
					<h2>Category</h2>
					<div class="col-md-12 form-group table-responsive checkbox" id="product_list">
						<button type="button" class="btn btn-default" aria-label="Add new Category" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Category/AddNew';">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add new Category
						</button> 
					</div>
					
					<div id="div_order_list" class="col-md-12 form-group table-responsive checkbox">
						<form name="agentForm" id="agentForm" action="<?php echo $mainPageUrl;?>/" method="post">
							<fieldset>
								<table class="table table-hover ">
									<thead>
										<tr>
											<th>Category Name</th>
											<th>Created</th>
											<th></th>
										</tr>
									</thead>
									
									<tbody>
										<?php
										foreach($categories as $list):
										?>
										<tr>
											<td><?php echo $list['IvCategory']['cate_title'];?></td>
											<td><?php echo $list['IvCategory']['created'];?></td>
											<td>
												<button type="button" class="btn btn-default btn-xs" title="modify" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Category/Modify/<?php echo $list['IvCategory']['cate_idx'];?>';">
													<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
												</button>
												<button type="button" class="btn btn-default btn-xs" title="delete" onclick="delCategory(<?php echo $list['IvCategory']['cate_idx'];?>);">
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
			
