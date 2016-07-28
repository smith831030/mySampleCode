
				<?php echo $this->Session->flash(); ?>
				<div class="row">
					<h2>Invoice</h2>
					<div class="col-md-12 form-group table-responsive checkbox" id="product_list">
						<button type="button" class="btn btn-default" aria-label="Create new invoice" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Invoice/AddNew';">
							<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Create new invoice
						</button> 
					</div>
					
					<div id="div_order_list" class="col-md-12 form-group table-responsive checkbox">
						<table class="table table-hover ">
							<thead>
								<tr>
									<th>Invoice No</th>
									<th>Invoice To</th>
									<th>Created</th>
									<th>GST</th>
									<th>GST Type</th>
									<th>Total</th>
									<th><?php echo $this->Paginator->sort('IvInvoice.i_status', 'Payment'); ?></th>
									<th></th>
								</tr>
							</thead>
							
							<tbody>
								<?php
								foreach($invoices as $list):
								?>
								<tr>
									<td onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Invoice/Detail/<?php echo $list['IvInvoice']['i_idx'];?>';"><?php echo $list['IvInvoice']['i_invoice_no'];?></td>
									<td onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Invoice/Detail/<?php echo $list['IvInvoice']['i_idx'];?>';"><?php echo $list['IvInvoiceCustomer']['ic_company'];?></td>
									<td onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Invoice/Detail/<?php echo $list['IvInvoice']['i_idx'];?>';"><?php echo $list['IvInvoice']['created'];?></td>
									<td onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Invoice/Detail/<?php echo $list['IvInvoice']['i_idx'];?>';">$<?php echo number_format($list['IvInvoice']['i_gst'], 2);?></td>
									<td onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Invoice/Detail/<?php echo $list['IvInvoice']['i_idx'];?>';">
										<?php 
										if($list['IvInvoice']['i_gst_type']==1)
											echo 'Non GST';
										elseif($list['IvInvoice']['i_gst_type']==2)
											echo '+GST';
										elseif($list['IvInvoice']['i_gst_type']==3)
											echo 'inc GST';
										elseif($list['IvInvoice']['i_gst_type']==4)
											echo 'Cash';
										?>
									</td>
									<td onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Invoice/Detail/<?php echo $list['IvInvoice']['i_idx'];?>';">$<?php echo number_format($list['IvInvoice']['i_total_price'], 2);?></td>
									<td>
										<?php 
										if($list['IvInvoice']['i_status']==1){
											echo '<span class="label label-default" onclick="showPaymentChange(1, '.$list['IvInvoice']['i_idx'].');">Unpaid</span>';
										}elseif($list['IvInvoice']['i_status']==2){
											echo '<span class="label label-primary" onclick="showPaymentChange(2, '.$list['IvInvoice']['i_idx'].');">Paid</span>';
										}elseif($list['IvInvoice']['i_status']==3){
											echo '<span class="label label-danger" onclick="showPaymentChange(3, '.$list['IvInvoice']['i_idx'].');">Close</span>';
										}
										?>
									</td>
									<td>
										<button type="button" class="btn btn-default btn-xs" title="modify" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Invoice/Modify/<?php echo $list['IvInvoice']['i_idx'];?>';">
											<span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
										</button>
										<button type="button" class="btn btn-default btn-xs" title="delete" onclick="delInvoice(<?php echo $list['IvInvoice']['i_idx'];?>);">
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
						<nav class="text-center">
							<ul class="pagination">
								<?php echo $this->Paginator->first(1, array('tag'=>'li', 'title'=>'First', 'ellipsis'=>'<li><a href="#">...</a></li>'));?>
								<?php echo $this->Paginator->numbers(array('tag' => 'li', 'separator'=>false, 'currentClass'=>'active', 'currentTag'=>'a'));?>
								<?php echo $this->Paginator->last(1, array('tag'=>'li', 'title'=>'Last', 'ellipsis'=>'<li><a href="#">...</a></li>'));?>
							</ul>
						</nav>
					</div>
				</div>
				
				<div class="dpNone" id="section_payment_change">
					<div class="screen container">
						<form action="<?php echo $mainPageUrl;?>/Managers/Invoice/ChangePayment" method="post">
							<fieldset>
								<h3>Payment Status Change</h3>
								<input type="hidden" name="data[IvInvoice][i_idx]" id="payment_i_idx" value="-1" />
								
								<input type="radio" class="i_payment" id="IvInvoicePayment1" name="data[IvInvoice][i_status]" value="1" /> <label class="status_list" for="IvInvoicePayment1"><span class="label label-default">Unpaid</span></label><br />
								<input type="radio" class="i_payment" id="IvInvoicePayment2" name="data[IvInvoice][i_status]" value="2" /> <label class="status_list" for="IvInvoicePayment2"><span class="label label-primary">Paid</span></label><br />
								<input type="radio" class="i_payment" id="IvInvoicePayment3" name="data[IvInvoice][i_status]" value="3" /> <label class="status_list" for="IvInvoicePayment3"><span class="label label-danger">Close</span></label><br />
								<br />
								<p class="text-right">
									<button type="submit" class="btn btn-primary">Change</button>
									<button type="button" class="btn btn-danger" onclick="$('#section_payment_change').hide();">Close</button>
								</p>
							</fieldset>
						</form>
					</div>
					<div class="layer_popup"></div>
				</div>
				
			<?php //echo $this->element('sql_dump'); ?>
			
