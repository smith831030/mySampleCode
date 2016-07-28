
				<?php echo $this->Session->flash('auth'); ?>
				<h2>Company > Add new company</h2>
					
				<div class="row">
					<div class="col-md-6">
						<?php
						echo $this->Form->create('IvInformation', array('class'=>'form-horizontal', 'type'=>'file'));
						echo $this->Form->input('if_idx', array('type'=>'hidden'));
						echo $this->Form->input('if_company', array('label'=>'Company', 'class'=>'form-control'));
						echo $this->Form->input('if_abn', array('label'=>'ABN', 'class'=>'form-control'));
						echo $this->Form->input('if_address', array('label'=>'Address', 'class'=>'form-control'));
						echo $this->Form->input('if_contact', array('label'=>'Contact', 'class'=>'form-control'));
						echo $this->Form->input('if_email', array('type'=>'email', 'label'=>'Email', 'class'=>'form-control'));
						echo $this->Form->input('if_logo_tmp', array('label'=>'Logo', 'class'=>'form-control', 'type'=>'file'));
						echo '<p class="alert alert-danger">* Document file name must be in English</p>';
						echo $this->Form->button('Modify', array('type'=>'submit', 'class'=>'btn btn-primary'));
						echo $this->Form->button('List', array('type'=>'button', 'class'=>'btn btn-default', 'onclick'=>'location.href=\''.$mainPageUrl.'/Managers/Company\''));
						echo $this->Form->end();
						?>
					</div>
				</div>
				
			<?php //echo $this->element('sql_dump'); ?>
			
