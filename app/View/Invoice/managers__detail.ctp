
				<?php echo $this->Session->flash(); ?>
				<h2>Invoice > Detail</h2>
				<?php
				if($detail['IvInvoice']['i_gst_type']==1){	//Non GST
					$gst_txt = 'INVOICE';
					$gst_type = 'Non GST';
				}elseif($detail['IvInvoice']['i_gst_type']==2){	//+GST
					$gst_txt = 'TAX INVOICE';
					$gst_type = '+GST';
				}elseif($detail['IvInvoice']['i_gst_type']==3){	//inc GST
					$gst_txt = 'TAX INVOICE';
					$gst_type = 'inc GST';
				}else{	//Cash
					$gst_txt = '';
					$gst_type = 'Cash';
				}
				?>
				<div class="invoice container">
					<div class="row">
						<div class="col-xs-6">
							<?php echo $this->Html->image('/upload/'.$detail['IvInvoiceInformation']['ii_logo'], array('class'=>'hidden_print_logo'));?>
						</div>
						<div class="col-xs-6 text-right">
							<br /><br />
							<strong><?php echo $gst_txt;?></strong>
						</div>
					</div>
					
					<div class="row">
						<div class="col-xs-6">
							<address>
								<strong>ABN</strong> <span><?php echo $detail['IvInvoiceInformation']['ii_abn'];?></span><br />
								<strong>Address</strong> <span><?php echo $detail['IvInvoiceInformation']['ii_address'];?></span><br />
								<strong>Contact</strong> <span><?php echo $detail['IvInvoiceInformation']['ii_contact'];?></span><br />
								<strong>Contact</strong> <span><?php echo $detail['IvInvoiceInformation']['ii_email'];?></span><br />
							</address>
						</div>
						<div class="col-xs-6">
							<address>
								<strong>Invoice No</strong> <span><?php echo $detail['IvInvoice']['i_invoice_no'];?></span><br />
								<strong>Issuing Date</strong> <span><?php echo date('Y-m-d', strtotime($detail['IvInvoice']['created']));?></span><br />
								<strong>Payment Due</strong> <span><?php echo $detail['IvInvoice']['i_payment_due'];?></span><br />
							</address>
						</div>
					</div>
					
					<div class="row">
						<div class="col-xs-12">
							<h4>Customer</h4>
							<strong>Name</strong> <span><?php echo $detail['IvInvoiceCustomer']['ic_company'];?></span><br />
							<strong>Address</strong> <span><?php echo $detail['IvInvoiceCustomer']['ic_address'];?></span><br />
							<strong>Contact</strong> <span><?php echo $detail['IvInvoiceCustomer']['ic_contact'];?></span><br />
							<?php if(!empty($detail['IvInvoiceCustomer']['ic_attention'])){?>
							<strong>Attention</strong> <span><?php echo $detail['IvInvoiceCustomer']['ic_attention'];?></span><br />
							<?php }?>
						</div>
					</div>
					
					<div class="row">
						<div class="col-xs-12 table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th>Description</th>
										<th>Product</th>
										<th>Price per Unit</th>
										<th>Cost</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($products as $list):?>
									<tr>
										<td><?php echo $list['IvCategory']['cate_title'].' '.$list['IvInvoiceProduct']['ip_name'];?></td>
										<td>
											<?php 
											echo $list['IvInvoiceProduct']['ip_qty'];
											if($list['IvInvoiceProduct']['ip_type']==2){
												echo 'weeks<br />';
												echo '('.$list['IvInvoiceProduct']['ip_startdate'].' - '.
														date('Y-m-d', strtotime($list['IvInvoiceProduct']['ip_startdate'].' +'.$list['IvInvoiceProduct']['ip_qty'].'weeks -1day'))
														.')';
											}elseif($list['IvInvoiceProduct']['ip_type']==3){
												echo 'months<br />';
												echo '('.$list['IvInvoiceProduct']['ip_startdate'].' - '.
														date('Y-m-d', strtotime($list['IvInvoiceProduct']['ip_startdate'].' +'.$list['IvInvoiceProduct']['ip_qty'].'months -1day'))
														.')';
											}
											?>
										</td>
										<td>$<?php echo number_format($list['IvInvoiceProduct']['ip_price'], 2);?></td>
										<td>$<?php echo number_format($list['IvInvoiceProduct']['ip_price'] * $list['IvInvoiceProduct']['ip_qty'], 2);?></td>
									</tr>
									<?php
									endforeach;
									unset($list);
									?>
								</tbody>
								<tfoot>
									<?php if($detail['IvInvoice']['i_discount']>0){?>
									<tr>
										<th colspan="3" class="text-right">Discount</th>
										<td>$<?php echo number_format($detail['IvInvoice']['i_discount'], 2);?></td>
									</tr>
									<?php }?>
									<tr>
										<th colspan="3" class="text-right">Sub Total</th>
										<td>$<?php echo number_format($detail['IvInvoice']['i_subtotal_price']-$detail['IvInvoice']['i_discount'], 2);?></td>
									</tr>
									<tr>
										<th colspan="3" class="text-right">GST(<?php echo $gst_type;?>)</th>
										<td>$<?php echo number_format($detail['IvInvoice']['i_gst'], 2);?></td>
									</tr>
									<tr>
										<th colspan="3" class="text-right">Total</th>
										<td>$<?php echo number_format($detail['IvInvoice']['i_total_price'], 2);?></td>
									</tr>
								</tfoot>
							</table>
							
							<?php if(!empty($detail['IvInvoice']['i_comment'])){?>
							<h4>Comment</h4>
							<div class="panel panel-default">
								<div class="panel-body">
									<?php echo nl2br($detail['IvInvoice']['i_comment']);?>
								</div>
							</div>
							<?php }?>
							
							<h4>Payment Details</h4>
							<p>
								<strong>Bank Name</strong> <span><?php echo $detail['ItBank']['b_name'];?></span><br />
								<strong>Account Name</strong> <span><?php echo $detail['ItBank']['b_acc_name'];?></span><br />
								<strong>Account Number</strong> <span><?php echo $detail['ItBank']['b_acc_no'];?></span><br />
								<?php
								if(!empty($detail['ItBank']['b_address'])){
									echo '<strong>Swift Code</strong> <span>'.$detail['ItBank']['b_bsb'].'</span><br />';
									echo '<strong>Bank Address</strong> <span>'.$detail['ItBank']['b_address'].'</span>';
								}else{
									echo '<strong>BSB</strong> <span>'.$detail['ItBank']['b_bsb'].'</span>';
								}
								?>
							</p>
						</div>
					</div>
					
					<div class="row">
						<div class="col-xs-12 text-right">
							<button type="button" class="btn btn-primary btn-lg" onclick="window.print();">PRINT</button>
							<button type="button" class="btn btn-default btn-lg" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Invoice/Modify/<?php echo $detail['IvInvoice']['i_idx'];?>';">MODIFY</button>
							<button type="button" class="btn btn-default btn-lg" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Invoice';">LIST</button>
						</div>
					</div>
				</div>
				<?php //echo $this->element('sql_dump'); ?>