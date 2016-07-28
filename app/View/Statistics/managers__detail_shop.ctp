				
				<?php
				$invoice_title='';
				if($detail['OrderList'][0]['Order']['order_gst_type']==1){
					$invoice_type='(Non GST)';
					$invoice_title='INVOICE';
				}elseif($detail['OrderList'][0]['Order']['order_gst_type']==2){
					$invoice_type='(+GST)';
					$invoice_title='TAX INVOICE';
				}elseif($detail['OrderList'][0]['Order']['order_gst_type']==3){
					$invoice_type='(inc GST)';
					$invoice_title='TAX INVOICE';
				}elseif($detail['OrderList'][0]['Order']['order_gst_type']==4){
					$invoice_type='(Cash)';
					$invoice_title='INVOICE';
				}
				?>
				<div class="row">
					<div class="col-xs-6">
						<?php echo $this->Html->image('common/logo.png', array('class'=>'hidden_print_logo')); ?>
					</div>
					<div class="col-xs-6 text-right">
						<br /><br />
						<strong><?php echo $invoice_title;?></strong>
					</div>
				</div>
				
				<div class="row">
					<div id="div_order_product_list" class="col-md-12 form-group table-responsive checkbox">
						<?php if($detail){ ?>
						<div id="print_invoice_info">
							<address>
								<strong><?php echo $detail['OrderList'][0]['Order']['admin_title'];?></strong><br />
								<strong>ABN</strong> <?php echo $detail['OrderList'][0]['Order']['admin_abn'];?><br />
								<strong>Address</strong> <?php echo $detail['OrderList'][0]['Order']['admin_address'];?><br />
								<strong>Contact</strong> <?php echo $detail['OrderList'][0]['Order']['admin_contact'];?><br />
								<strong>Date</strong> <?php echo date('j/M/Y');?><br />
								<strong>Invoice no</strong> <?php echo $detail['OrderList'][0]['Order']['order_code'];?>
							</address>
							
							<address>
								<strong>Bill to : <?php echo $detail['OrderList'][0]['Shop']['shop_title'];?></strong><br />
								<strong>Address : </strong><?php echo $detail['OrderList'][0]['Shop']['shop_address'];?><br />
								<strong>Contact : </strong><?php echo $detail['OrderList'][0]['Shop']['shop_contact'];?>
							</address>
						</div>
						
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Product name</th>
									<th>EA per box</th>
									<th>Price per box</th>
									<?php if($detail['OrderList'][0]['Order']['order_status']<2 || $detail['OrderList'][0]['Order']['order_status']>=4){?>
										<th>Order qty(box)</th>
										<th>Released qty</th>
										<th>Total</th>
										<th>Comment</th>
									<?php }else{?>
										<th>Order qty<br />box(ea)</th>
									<?php }?>
								</tr>
							</thead>
							
							<tbody>
								<?php
								$total_price=0;
								foreach($detail['OrderList'] as $key=>$list):
									$op_order_qty=$list['OrderProduct']['op_order_qty']/$list['OrderProduct']['pro_bottle_per_box'];
									$op_release_qty=$list['OrderProduct']['op_release_qty']/$list['OrderProduct']['pro_bottle_per_box'];
									$total[$key]=$list['OrderProduct']['pro_bottle_per_box'] * $list['OrderProduct']['pro_price_per_bottle'] * $op_release_qty;
									$total_price+=$total[$key];
									
									$class='';
									if($list['OrderProduct']['op_order_qty']!=$list['OrderProduct']['op_release_qty'])
										$class='class="danger"';
								?>
								<tr <?php echo $class;?>>
									<td><?php echo $list['P']['pro_name'];?></td>
									<td><?php echo $list['OrderProduct']['pro_bottle_per_box'];?></td>
									<td>$<?php echo number_format($list['OrderProduct']['pro_bottle_per_box'] * $list['OrderProduct']['pro_price_per_bottle'], 2);?></td>
									
									<?php if($detail['OrderList'][0]['Order']['order_status']<2 || $detail['OrderList'][0]['Order']['order_status']>=4){?>
										<td><?php echo $op_order_qty;?></td>
										<td><?php echo $op_release_qty;?></td>
										<td>$<?php echo number_format($total[$key], 2);?></td>
										<td><?php echo $list['OrderProduct']['op_comment'];?></td>
									<?php }else{?>
										<td>
										<?php 
										if($list['OrderProduct']['op_order_qty'] && $list['OrderProduct']['pro_bottle_per_box']){
											echo intval($list['OrderProduct']['op_order_qty']/$list['OrderProduct']['pro_bottle_per_box']).' ('.$list['OrderProduct']['op_order_qty']%$list['OrderProduct']['pro_bottle_per_box'].')';
										}else{
											echo '0';
										}
										?>
										</td>
									<?php }?>
									
								</tr>
								<?php
								endforeach;
								unset($list);
								?>
							</tbody>
							
							<?php if($detail['OrderList'][0]['Order']['order_status']<2 || $detail['OrderList'][0]['Order']['order_status']>=4){?>
							<tfoot>
								<tr>
									<?php 
									$order_discount = $detail['OrderList'][0]['Order']['order_discount'];
									$total_price-=$order_discount;
									if($order_discount>0){
									?>
									<td colspan="4"></td>
									<th>Discount</th>
									<td>- $<?php echo number_format($order_discount,2);?></td>
									<td></td>
									<?php }?>
								<?php if($detail['OrderList'][0]['Order']['order_status']<2 || $detail['OrderList'][0]['Order']['order_status']>=4){?>
								</tr>
								
								<tr>
									<td colspan="4"></td>
									<th>Sub-total</th>
									<td>
										<span id="total_price_span">$<?php echo number_format($total_price,2);?></span>
										<input type="hidden" id="total_price" value="<?php echo $total_price;?>" />
									</td>
									<td></td>
								</tr>
								
								<tr>
									<td colspan="4"></td>
									<th>
										GST
										<?php echo $invoice_type;?>
									</th>
									<td>
									<?php
									$gst=0;
									if($detail['OrderList'][0]['Order']['order_gst_type']==2){
										$gst = $total_price*0.1;
										$total_price+=$gst;
									}else if($detail['OrderList'][0]['Order']['order_gst_type']==3){
										$gst = $total_price/11;
									}
									
									echo '$'.number_format($gst, 2);
									?>
									</td>
									<td></td>
									
								</tr>
								
								<tr>
									<td colspan="4"></td>
									<th>Total</th>
									<td>$<?php echo number_format($total_price, 2);?></td>
									<td></td>
									
								<?php }else{?>
									<td colspan="4"></td>
									<td>
										<span id="total_price_span">$<?php echo number_format($total_price, 2);?></span>
										<input type="hidden" id="total_price" value="<?php echo $total_price;?>" />
									</td>
								<?php }?>
								</tr>
							</tfoot>
							<?php }?>
						</table>
								
						<?php if($detail['OrderList'][0]['Order']['order_comment']){?>
						<h4>Comment</h4>
						<blockquote>
							<p><?php echo nl2br($detail['OrderList'][0]['Order']['order_comment']); ?></p>
						</blockquote>
						<?php }?>
								
						<?php if($detail['OrderListService']){?>
						<!-- Service items -->
						<h4>Free items</h4>
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Product name</th>
									<th>Ea per box</th>
									<th>Price per box</th>
									<th>
										Released qty<br />
										box(ea)
									</th>
								</tr>
							</thead>
							
							<tbody>
								<?php
								foreach($detail['OrderListService'] as $key=>$list):
								?>
								<tr>
									<td><?php echo $list['P']['pro_name'];?></td>
									<td><?php echo $list['OrderProduct']['pro_bottle_per_box'];?></td>
									<td>$<?php echo number_format($list['OrderProduct']['pro_bottle_per_box'] * $list['OrderProduct']['pro_price_per_bottle'], 2);?></td>
									<td><?php echo intval($list['OrderProduct']['op_release_qty']/$list['OrderProduct']['pro_bottle_per_box']).' ('.$list['OrderProduct']['op_release_qty']%$list['OrderProduct']['pro_bottle_per_box'].')';?></td>
								</tr>
								<?php
								endforeach;
								unset($list);
								?>
							</tbody>
						</table>
						<?php }?>
								
						<div id="print_payment_detail">
							<h4>Payment Details</h4>
							<ul>
								<li><strong>Account Name</strong> Australian General Services Pty Ltd</li>
								<li><strong>BSB</strong> 033 009</li>
								<li><strong>Account No</strong> 53 23 54</li>
							</ul>
						</div>
						<?php }?>
					</div>
				</div>
				
				<div class="row">
					<div class="col-xs-12 text-right">
						<button type="button" class="btn btn-primary btn-lg" onclick="window.print();">PRINT</button>
						<button type="button" class="btn btn-default btn-lg" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Statistics';">LIST</button>
					</div>
				</div>
				
			<?php //echo $this->element('sql_dump'); ?>
			
