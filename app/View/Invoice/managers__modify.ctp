
				<?php echo $this->Session->flash(); ?>
				<?php echo $this->Html->css('jquery-ui.min.css', array('inline' => false));?>
				<?php echo $this->Html->script('jquery-ui.min.js', array('inline' => false));?>
				<script>
				jQuery(function() {
					jQuery( ".payment_due, .startdate" ).datepicker({
						dateFormat:'yy-mm-dd'
					});
				});
				</script>
				
				<h2>Invoice > Modify</h2>
				<?php 
				echo $this->Form->create('IvInvoice', array('class'=>'form-horizontal')); 
				
				echo $this->Form->input('i_idx', array('type'=>'hidden'));
				echo $this->Form->input('IvInvoiceInformation.ii_idx', array('type'=>'hidden'));
				echo $this->Form->input('IvInvoiceCustomer.ic_idx', array('type'=>'hidden'));
				?>
				<div class="row">
					<div class="col-md-6">
						<?php
						echo $this->Form->input('IvInvoiceInformation.if_idx', array('options'=>$companies, 'empty'=>'Company Selection', 'label'=>'Company', 'class'=>'form-control', 'required'));
						echo $this->Form->input('b_idx', array('options'=>$banks, 'empty'=>'Bank Selection', 'label'=>'Bank', 'class'=>'form-control', 'required'));
						echo $this->Form->input('i_payment_due', array('type'=>'text', 'label'=>'Payment Due', 'class'=>'form-control payment_due', 'placeholder'=>'YYYY-MM-DD', 'required'));
						?>
					</div>
					
					<div class="col-md-6">
						<?php
						echo $this->Form->input('IvInvoiceCustomer.c_idx', array('options'=>$customers, 'empty'=>'(Customise)', 'label'=>'Customer', 'class'=>'form-control', 'onchange'=>'setCustomer(this); return false;'));
						
						echo $this->Form->input('IvInvoiceCustomer.ic_company', array('placeholder'=>'Company', 'label'=>false, 'class'=>'form-control'));
						echo $this->Form->input('IvInvoiceCustomer.ic_address', array('placeholder'=>'Address', 'label'=>false, 'class'=>'form-control'));
						echo $this->Form->input('IvInvoiceCustomer.ic_contact', array('placeholder'=>'Contact', 'label'=>false, 'class'=>'form-control'));
						echo $this->Form->input('IvInvoiceCustomer.ic_attention', array('placeholder'=>'Attention', 'label'=>false, 'class'=>'form-control'));
						echo $this->Form->input('IvInvoiceCustomer.ic_email', array('placeholder'=>'Email', 'label'=>false, 'class'=>'form-control'));
						?>
					</div>
				</div>
				<br />
				
				<div class="row">
					<div class="col-md-12">
						<strong>Product List</strong>
					</div>
					
					<div class="col-md-5">
						<div class="list-group">
						<?php
						$current_cate_idx='';
						foreach($products as $list):
							if($current_cate_idx!=$list['IvCategory']['cate_idx']){
								echo '<a href="#" class="list-group-item active" onclick="toggleSection(\'group-'.$list['IvCategory']['cate_idx'].'\', this);">
										<span class="glyphicon glyphicon-minus"></span> '.
										$list['IvCategory']['cate_title']
									.'</a>';
								$current_cate_idx=$list['IvCategory']['cate_idx'];
							}
							echo '<a href="#" class="list-group-item group-'.$list['IvCategory']['cate_idx'].'" onclick="add_program('.$list['IvCategory']['cate_idx'].', '.$list['IvProduct']['pro_idx'].', \''.$list['IvProduct']['pro_name'].'\', '.$list['IvProduct']['pro_type'].', '.$list['IvProduct']['pro_price'].');">'.
									$list['IvProduct']['pro_name']
									.' <strong>$'.number_format($list['IvProduct']['pro_price'], 2).'</strong>'
								.' <span class="glyphicon glyphicon glyphicon-plus"></span>
								</a>';
						endforeach;
						unset($list);
						?>
						</div>
					</div>
					
					<div class="col-md-7">
						<ul class="list-group list_programs">
							<?php foreach($invoice_products as $list):?>
							<li class="list-group-item product_list">
								<div class="form-group-sm">
									<input type="hidden" name="data[IvInvoiceProduct][cate_idx][]" value="<?php echo $list['IvInvoiceProduct']['cate_idx'];?>">
									<input type="hidden" name="data[IvInvoiceProduct][pro_idx][]" value="<?php echo $list['IvInvoiceProduct']['pro_idx'];?>">
									<input type="hidden" name="data[IvInvoiceProduct][ip_name][]" value="<?php echo $list['IvInvoiceProduct']['ip_name'];?>">
									<input type="hidden" name="data[IvInvoiceProduct][ip_price][]" class="ip_price" value="<?php echo $list['IvInvoiceProduct']['ip_price'];?>">
									<input type="hidden" name="data[IvInvoiceProduct][ip_type][]" value="<?php echo $list['IvInvoiceProduct']['ip_type'];?>">
									<?php if($list['IvInvoiceProduct']['ip_type']==1){?>
										<div class="col-sm-6"><?php echo $list['IvInvoiceProduct']['ip_name'];?> <strong>$<?php echo number_format($list['IvInvoiceProduct']['ip_price'], 2);?></strong></div>
										<div class="col-sm-2 col-sm-offset-3"><input type="number" name="data[IvInvoiceProduct][ip_qty][]" id="ip_qty" class="form-control" placeholder="Ea" value="1" onchange="calc_price();" value="<?php echo $list['IvInvoiceProduct']['ip_qty'];?>" required="required"></div>
										<input type="hidden" name="data[IvInvoiceProduct][ip_startdate][]" value="0000-00-00">
									<?php }else{?>
										<div class="col-sm-6"><?php echo $list['IvInvoiceProduct']['ip_name'];?> <strong>$<?php echo number_format($list['IvInvoiceProduct']['ip_price'], 2);?></strong></div>
										<div class="col-sm-3"><input type="text" name="data[IvInvoiceProduct][ip_startdate][]" class="form-control startdate" placeholder="YYYY-MM-DD" value="<?php echo $list['IvInvoiceProduct']['ip_startdate'];?>" required="required"></div>
										<div class="col-sm-2"><input type="number" name="data[IvInvoiceProduct][ip_qty][]" id="ip_qty" class="form-control" placeholder="Week" onchange="calc_price();" value="<?php echo $list['IvInvoiceProduct']['ip_qty'];?>" required="required"></div>
									<?php }?>
									<div class="col-sm-1"><button class="btn btn-default btn-xs" onclick="remove_program(this);"><span class="glyphicon glyphicon glyphicon-minus"></span></button></div>
								</div>
								<div class="clearfix"></div>
							</li>
							<?php
							endforeach;
							unset($list);
							?>
						</ul>
						
						<div class="col-sm-5 col-sm-offset-7">
							<?php
							echo $this->Form->input('i_discount', array('type'=>'number', 'label'=>false, 'placeholder'=>'Discount', 'id'=>'i_discount', 'step'=>'any', 'class'=>'form-control', 'onchange'=>'calc_price();', 'required'));
							echo '<p class="text-right"><strong>Sub Total</strong> <span id="span_subtotal">$'.number_format($this->request->data['IvInvoice']['i_subtotal_price']-$this->request->data['IvInvoice']['i_discount'], 2).'</span></p>';
							$gst_options=array(1=>'Non GST', 2=>'+GST', 3=>'inc GST', 4=>'Cash');
							echo $this->Form->input('i_gst_type', array('options'=>$gst_options, 'label'=>false, 'empty'=>'GST Selection', 'id'=>'i_gst_type', 'class'=>'form-control', 'onchange'=>'calc_price();', 'required'));
							echo '<p class="text-right"><strong>GST</strong> <span id="span_gst">$'.number_format($this->request->data['IvInvoice']['i_gst'], 2).'</span></p>';
							echo '<p class="text-right"><strong>Total</strong> <span id="span_total">$'.number_format($this->request->data['IvInvoice']['i_total_price'], 2).'</span></p>';
							?>
						</div>
						<?php echo $this->Form->input('i_comment', array('type'=>'textarea', 'rows'=>3, 'label'=>false, 'placeholder'=>'Comment', 'class'=>'form-control'));?>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
					<?php
						echo '<br />';
						echo $this->Form->button('Modify', array('type'=>'submit', 'class'=>'btn btn-lg btn-primary btn-block'));
					?>
					</div>
				</div>
				<?php echo $this->Form->end();?>
				
			<?php //echo $this->element('sql_dump'); ?>