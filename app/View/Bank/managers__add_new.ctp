
				<?php echo $this->Session->flash('auth'); ?>
				<h2>Bank > Add new bank</h2>
					
				<div class="row">
					<div class="col-md-6">
						<?php
						echo $this->Form->create('ItBank', array('class'=>'form-horizontal'));
						echo $this->Form->input('b_name', array('label'=>'Bank Name', 'class'=>'form-control'));
						echo $this->Form->input('b_acc_no', array('label'=>'Account No', 'class'=>'form-control'));
						echo $this->Form->input('b_acc_name', array('label'=>'Account Name', 'class'=>'form-control'));
						echo $this->Form->input('b_bsb', array('label'=>'BSB / Swift Code', 'class'=>'form-control'));
						echo $this->Form->input('b_address', array('label'=>'Bank Address', 'class'=>'form-control'));
						echo $this->Form->button('Create Bank', array('type'=>'submit', 'class'=>'btn btn-default'));
						echo $this->Form->end();
						?>
					</div>
				</div>
				
			<?php //echo $this->element('sql_dump'); ?>
			
