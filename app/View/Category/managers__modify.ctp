
				<?php echo $this->Session->flash(); ?>
				<h2>Category > Modify</h2>
					
				<div class="row">
					<div class="col-md-6">
						<?php
						echo $this->Form->create('IvCategory', array('class'=>'form-horizontal'));
						echo $this->Form->input('cate_idx', array('type'=>'hidden'));
						echo $this->Form->input('cate_title', array('label'=>'Category Name', 'class'=>'form-control'));
						echo $this->Form->button('Create Category', array('type'=>'submit', 'class'=>'btn btn-default'));
						echo $this->Form->end();
						?>
					</div>
				</div>
				
			<?php //echo $this->element('sql_dump'); ?>