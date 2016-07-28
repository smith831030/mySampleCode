
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
				
				<div class="col-md-6 form-group table-responsive">
					<h2>Payable > Create new payment</h2>
					<?php 
					echo $this->Form->create('IvPayable', array('class'=>'form-horizontal')); 
					echo $this->Form->input('ip_idx', array('type'=>'hidden'));
					echo $this->Form->input('ip_invoice_from', array('label'=>'Invoice From', 'class'=>'form-control', 'required'));
					echo $this->Form->input('ip_invoice_no', array('label'=>'Invoice No', 'class'=>'form-control', 'required'));
					echo $this->Form->input('ip_due_date', array('type'=>'text', 'label'=>'Due Date', 'class'=>'form-control payment_due', 'required'));
					echo $this->Form->input('ip_subtotal', array('type'=>'number', 'label'=>'Sub Total', 'class'=>'form-control', 'onchange'=>'calc_price_payable()', 'required'));
					$gst_options=array(1=>'Non GST', 2=>'+GST', 3=>'inc GST', 4=>'Cash');
					echo $this->Form->input('ip_gst_type', array('options'=>$gst_options, 'label'=>false, 'empty'=>'GST Selection', 'id'=>'ip_gst_type', 'class'=>'form-control', 'onchange'=>'calc_price_payable();', 'required'));
					echo '<p><label>GST</label> <span id="span_gst">$'.number_format($this->request->data['IvPayable']['ip_gst'], 2).'</span></p>';
					echo '<p><label>Total</label> <span id="span_total">$'.number_format($this->request->data['IvPayable']['ip_total'], 2).'</span></p>';
					echo $this->Form->input('ip_comment', array('type'=>'textarea', 'label'=>'Comment', 'class'=>'form-control'));
					
					echo '<br />';
					echo $this->Form->button('Modify', array('type'=>'submit', 'class'=>'btn btn-lg btn-primary btn-block'));
					
					echo $this->Form->end();?>
				</div>
			<?php //echo $this->element('sql_dump'); ?>