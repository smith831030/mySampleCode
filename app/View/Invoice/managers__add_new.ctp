
				<?php echo $this->Session->flash(); ?>
				<?php echo $this->Html->css('jquery-ui.min.css', array('inline' => false));?>
				<?php echo $this->Html->script('jquery-ui.min.js', array('inline' => false));?>
				<script>
				jQuery(function() {
					jQuery( ".payment_due" ).datepicker({
						dateFormat:'yy-mm-dd'
					});
				});
				</script>
				
				<h2>Invoice > Create new Invoice</h2>
				<?php echo $this->Form->create('IvInvoice', array('class'=>'form-horizontal', 'onsubmit'=>'return checkCreateInvoice();')); ?>
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
					<div class="col-md-3">
						<strong>Product List</strong>
					</div>
					<div class="col-md-2">
						<p class="text-right">
							<button type="button" class="btn btn-default" aria-label="Create new invoice" onclick="add_program_new(); return false;">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add New Product
							</button>
						</p>
					</div>
					<div class="clearfix"></div>
					
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
							echo '<a href="#" class="list-group-item group-'.$list['IvCategory']['cate_idx'].'" onclick="add_program('.$list['IvCategory']['cate_idx'].', '.$list['IvProduct']['pro_idx'].', \''.$list['IvProduct']['pro_name'].'\', '.$list['IvProduct']['pro_type'].', '.$list['IvProduct']['pro_price'].'); return false;">'.
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
						<?php
						/*
						
						$options=array('1'=>'Ea', '2'=>'Per Week');
						echo $this->Form->input('pro_type', array('label'=>false, 'type'=>'select', 'options'=>$options, 'placeholder'=>'Type', 'class'=>'form-control'));
						*/
						?>
						<ul class="list-group list_programs">
						</ul>
						
						<div class="col-sm-5 col-sm-offset-7">
							<?php
							echo $this->Form->input('i_discount', array('type'=>'number', 'label'=>false, 'placeholder'=>'Discount', 'id'=>'i_discount', 'step'=>'any', 'class'=>'form-control', 'onchange'=>'calc_price();', 'required'));
							echo '<p class="text-right"><strong>Sub Total</strong> <span id="span_subtotal">$0</span></p>';
							$gst_options=array(1=>'Non GST', 2=>'+GST', 3=>'inc GST', 4=>'Cash');
							echo $this->Form->input('i_gst_type', array('options'=>$gst_options, 'label'=>false, 'empty'=>'GST Selection', 'id'=>'i_gst_type', 'class'=>'form-control', 'onchange'=>'calc_price();', 'required'));
							echo '<p class="text-right"><strong>GST</strong> <span id="span_gst">$0</span></p>';
							echo '<p class="text-right"><strong>Total</strong> <span id="span_total">$0</span></p>';
							?>
						</div>
						<?php echo $this->Form->input('i_comment', array('type'=>'textarea', 'rows'=>3, 'label'=>false, 'placeholder'=>'Comment', 'class'=>'form-control'));?>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
					<?php
						echo '<br />';
						echo $this->Form->button('Create Invoice', array('type'=>'submit', 'class'=>'btn btn-lg btn-primary btn-block'));
					?>
					</div>
				</div>
				<?php echo $this->Form->end();?>
				
			<?php //echo $this->element('sql_dump'); ?>