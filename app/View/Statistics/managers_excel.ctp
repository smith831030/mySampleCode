			<div class="row">
				<h2>Statistics</h2>
				
				<div class="col-md-2 col-offset-md-10">
					<strong>Total :</strong> <?php echo $total;?>
				</div>
				
				<div class="col-lg-12">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Invoice Type</th>
								<th>Invoice No</th>
								<th>Invoice To</th>
								<th>Created</th>
								<th>Bank</th>
								<th>
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
								</th>
								<th>Total</th>
								<th>
										<?php
										if(empty($this->params['named']['payment']) || $this->params['named']['payment']==0)
											echo 'Payment';
										elseif($this->params['named']['payment']==1)
											echo 'Unpaid';
										elseif($this->params['named']['payment']==2)
											echo 'Paid';
										?>
								</th>
								<th>Payment Date</th>
							</tr>
						</thead>
						
						<tbody>
						<?php 
						foreach($invoices as $list):
						?>
							<tr>
								<td>
								<?php
								if($list['AllInvoice']['invoice_type']=='HB'){
									echo 'Hojubada';
								}elseif($list['AllInvoice']['invoice_type']=='ED'){
									echo 'E-dong';
								}elseif($list['AllInvoice']['invoice_type']='IT'){
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
								if(($list['AllInvoice']['invoice_type']=='ED' && $list['AllInvoice']['status']==5) || ($list['AllInvoice']['invoice_type']!='ED' && $list['AllInvoice']['status']==2)){
									echo '<span class="label label-primary">Paid</span>';
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
				</div>
			</div>
			
			<?php //echo $this->element('sql_dump'); ?>