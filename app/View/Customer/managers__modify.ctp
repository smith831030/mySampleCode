
				<?php echo $this->Session->flash('auth'); ?>
				<h2>Customer > Modify</h2>
					
				<div class="row">
					<div class="col-md-6">
						<?php
						echo $this->Form->create('IvCustomer', array('class'=>'form-horizontal'));
						echo $this->Form->input('c_idx', array('type'=>'hidden'));
						echo $this->Form->input('c_company', array('label'=>'Company', 'class'=>'form-control'));
						echo $this->Form->input('c_description', array('label'=>'Description', 'class'=>'form-control'));
						echo $this->Form->input('c_address', array('label'=>'Address', 'class'=>'form-control'));
						echo $this->Form->input('c_contact', array('label'=>'Contact', 'class'=>'form-control'));
						echo $this->Form->input('c_attention', array('label'=>'Attention', 'class'=>'form-control'));
						echo $this->Form->input('c_email', array('label'=>'Email', 'class'=>'form-control'));
						echo $this->Form->button('Modify', array('type'=>'submit', 'class'=>'btn btn-default'));
						echo $this->Form->end();
						?>
					</div>
				</div>
				
			<?php //echo $this->element('sql_dump'); ?>
			
