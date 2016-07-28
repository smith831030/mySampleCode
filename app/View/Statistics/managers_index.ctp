
			<?php echo $this->Html->css('jquery-ui.min.css', array('inline' => false));?>
			<?php echo $this->Html->script('jquery-ui.min.js', array('inline' => false));?>
			<?php echo $this->Html->script('https://www.google.com/jsapi', array('inline' => false));?>
			<?php 
			include_once('common/array_coulmn.php');
			?>
			<script>
				jQuery(function() {
					jQuery( ".startDate" ).datepicker({
						dateFormat:'yy-mm-dd',
						defaultDate: "+1w",
						numberOfMonths: 3,
						onClose: function( selectedDate ) {
						jQuery( ".endDate" ).datepicker( "option", "minDate", selectedDate );
						}
					});
					jQuery( ".endDate" ).datepicker({
						dateFormat:'yy-mm-dd',
						defaultDate: "+1w",
						numberOfMonths: 3,
						onClose: function( selectedDate ) {
						jQuery( ".startDate" ).datepicker( "option", "maxDate", selectedDate );
						}
					});
					
				});
			</script>
			
			<div class="row">
				<h2>Statistics</h2>
				<div id="div_search" class="col-md-10 form-group">
					<form id="searchForm" method="get" action="" class="form-inline">
						<fieldset>
							<div class="form-group">
								<input type="text" class="startDate form-control" name="startDate" value="<?php if(isset($startDate)) echo $startDate;?>" placeholder="start date" />~
							</div>
							<div class="form-group">
								<input type="text" class="endDate form-control" name="endDate" value="<?php if(isset($endDate)) echo $endDate;?>" placeholder="end date" />
							</div>
							<button class="btn btn-default" type="submit">Search</button>
						</fieldset>
					</form>
				</div>
				<div class="col-md-2">
					<strong>Total :</strong> <?php echo $total;?>
					<button type="button" class="btn btn-success" onclick="save_excel('<?php echo $this->Paginator->url();?>');">Save Excel</button>
				</div>
				
				<div class="col-lg-12">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
										<?php
										if(empty($this->params['named']['invoice_type']) || $this->params['named']['invoice_type']==0){
											echo 'Invoice Type';
										}elseif($this->params['named']['invoice_type']=='HB'){
											echo 'Hojubada';
										}elseif($this->params['named']['invoice_type']=='ED'){
											echo 'Shop';
										}elseif($this->params['named']['invoice_type']='IT'){
											echo 'Internship';
										}elseif($this->params['named']['invoice_type']=='ST'){
											echo 'Settlement';
										}elseif($this->params['named']['invoice_type']=='IS'){
											echo 'Internship & Settlement';
										}
										?>
										
										<span class="caret"></span>
										</button>
										<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
											<?php
												//Invoice To
												echo '<li>'.$this->Paginator->link('All', array('page'=>1, 'invoice_type'=>0)).'</li>';
												echo '<li>'.$this->Paginator->link('Hojubada', array('page'=>1, 'invoice_type'=>'HB')).'</li>';
												echo '<li>'.$this->Paginator->link('Shop', array('page'=>1, 'invoice_type'=>'ED')).'</li>';
												echo '<li>'.$this->Paginator->link('Internship', array('page'=>1, 'invoice_type'=>'IT')).'</li>';
												echo '<li>'.$this->Paginator->link('Settlement', array('page'=>1, 'invoice_type'=>'ST')).'</li>';
												echo '<li>'.$this->Paginator->link('Internship & Settlement', array('page'=>1, 'invoice_type'=>'IS')).'</li>';
											?>
										</ul>
									</div>
								</th>
								<th>Invoice No</th>
								<th>
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
										<?php
										if(empty($this->params['named']['invoice_to']) || $this->params['named']['invoice_to']==0)
											echo 'Invoice To';
										else
											echo $this->params['named']['invoice_to'];
										?>
										
										<span class="caret"></span>
										</button>
										<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
											<?php
												//Invoice To
												echo '<li>'.$this->Paginator->link('All', array('page'=>1, 'invoice_to'=>0)).'</li>';
												
												foreach($invoice_to as $list):
													echo '<li>'.$this->Paginator->link($list['AllInvoice']['invoice_to'], array('page'=>1, 'invoice_to'=>$list['AllInvoice']['invoice_to'])).'</li>';
												endforeach;
												unset($list);
											?>
										</ul>
									</div>
								</th>
								<th>Created</th>
								<th>Bank</th>
								<th>
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
										<?php
										if(empty($this->params['named']['gst_type']) || $this->params['named']['gst_type']==0)
											echo 'GST Type';
										elseif($this->params['named']['gst_type']==1)
											echo 'Non GST';
										elseif($this->params['named']['gst_type']==2)
											echo '+ GST';
										elseif($this->params['named']['gst_type']==3)
											echo 'inc GST';
										elseif($this->params['named']['gst_type']==4)
											echo 'Cash';
										?>
										
										<span class="caret"></span>
										</button>
										<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
											<?php
												//GST Type
												echo '<li>'.$this->Paginator->link('All', array('page'=>1, 'gst_type'=>0)).'</li>';
												echo '<li>'.$this->Paginator->link('Non GST', array('page'=>1, 'gst_type'=>1)).'</li>';
												echo '<li>'.$this->Paginator->link('+ GST', array('page'=>1, 'gst_type'=>2)).'</li>';
												echo '<li>'.$this->Paginator->link('inc GST', array('page'=>1, 'gst_type'=>3)).'</li>';
												echo '<li>'.$this->Paginator->link('Cash', array('page'=>1, 'gst_type'=>4)).'</li>';
											?>
										</ul>
									</div>
								</th>
								<th>Total</th>
								<th>
									<div class="dropdown">
										<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
										<?php
										if(empty($this->params['named']['payment']) || $this->params['named']['payment']==0)
											echo 'Payment';
										elseif($this->params['named']['payment']==1)
											echo 'Unpaid';
										elseif($this->params['named']['payment']==2)
											echo 'Paid';
										?>
										<span class="caret"></span>
										</button>
										<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
											<?php
												//GST Type
												echo '<li>'.$this->Paginator->link('All', array('page'=>1, 'payment'=>0)).'</li>';
												echo '<li>'.$this->Paginator->link('Unpaid', array('page'=>1, 'payment'=>1)).'</li>';
												echo '<li>'.$this->Paginator->link('Paid', array('page'=>1, 'payment'=>2)).'</li>';
												?>
										</ul>
									</div>
								</th>
								<th>Payment Date</th>
							</tr>
						</thead>
						
						<tbody>
						<?php 
						foreach($invoices as $list):
						?>
							<tr onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Statistics/Detail/<?php echo $list['AllInvoice']['invoice_type'];?>/<?php echo $list['AllInvoice']['idx'];?>';">
								<td>
								<?php
								if($list['AllInvoice']['invoice_type']=='HB'){
									echo 'Hojubada';
								}elseif($list['AllInvoice']['invoice_type']=='ED'){
									echo 'Shop';
								}elseif($list['AllInvoice']['invoice_type']=='IT'){
									echo 'Internship';
								}elseif($list['AllInvoice']['invoice_type']=='ST'){
									echo 'Settlement';
								}elseif($list['AllInvoice']['invoice_type']=='IS'){
									echo 'Internship & Settlement';
								}
								?>
								</td>
								<td><?php echo $list['AllInvoice']['invoice_no'];?></td>
								<td><?php echo $list['AllInvoice']['invoice_to'];?></td>
								<td><?php echo $list['AllInvoice']['created'];?></td>
								<td><?php echo $list['AllInvoice']['bank'];?></td>
								<td>
								<?php 
								if($list['AllInvoice']['gst_type']==1)
									echo 'Non GST';
								elseif($list['AllInvoice']['gst_type']==2)
									echo '+GST';
								elseif($list['AllInvoice']['gst_type']==3)
									echo 'inc GST';
								elseif($list['AllInvoice']['gst_type']==4)
									echo 'Cash';
								?>
								</td>
								<td>$<?php echo number_format($list['AllInvoice']['total_price'], 2);?></td>
								<td>
								<?php 
								$arr_internship=array('IT','ST','IS');
								if(($list['AllInvoice']['invoice_type']=='ED' && $list['AllInvoice']['status']==5) || ($list['AllInvoice']['invoice_type']!='ED' && $list['AllInvoice']['status']==2)){
									echo '<span class="label label-primary">Paid</span>';
								}elseif(in_array($list['AllInvoice']['invoice_type'], $arr_internship) && $list['AllInvoice']['status']==3){
									echo '<span class="label label-danger">Closed</span>';
								}elseif(in_array($list['AllInvoice']['invoice_type'], $arr_internship) && $list['AllInvoice']['status']==4){
									echo '<span class="label label-warning">Refunded</span>';
								}else{
									echo '<span class="label label-default">Unpaid</span>';
								}
								?>
								</td>
								<td><?php if(!empty($list['AllInvoice']['payment_date'])) echo date('Y-m-d', strtotime($list['AllInvoice']['payment_date']));?></td>
							</tr>
						<?php
						endforeach;
						unset($list);
						?>
						</tbody>
					</table>
					<nav class="text-center">
						<ul class="pagination">
							<?php echo $this->Paginator->first(1, array('tag'=>'li', 'title'=>'First', 'ellipsis'=>'<li><a href="#">...</a></li>'));?>
							<?php echo $this->Paginator->numbers(array('tag' => 'li', 'separator'=>false, 'currentClass'=>'active', 'currentTag'=>'a'));?>
							<?php echo $this->Paginator->last(1, array('tag'=>'li', 'title'=>'Last', 'ellipsis'=>'<li><a href="#">...</a></li>'));?>
						</ul>
					</nav>
				</div>
			</div>
			
			<?php //echo $this->element('sql_dump'); ?>