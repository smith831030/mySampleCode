
				<?php echo $this->Session->flash('auth'); ?>
				<h2>Product > Add new Product</h2>
					
				<div class="row">
					<div class="col-md-6">
						<?php
						echo $this->Form->create('IvProduct', array('class'=>'form-horizontal'));
						echo $this->Form->input('cate_idx', array('type'=>'select', 'options'=>$categories, 'label'=>'Category', 'class'=>'form-control'));
						echo $this->Form->input('pro_name', array('label'=>'Product Name', 'class'=>'form-control'));
						echo $this->Form->input('pro_price', array('type'=>'number', 'step'=>'any', 'label'=>'Price', 'class'=>'form-control', 'placeholder'=>'$'));
						$options=array('1'=>'Ea', '2'=>'Per Week');
						echo $this->Form->input('pro_type', array('type'=>'select', 'options'=>$options, 'label'=>'Type', 'class'=>'form-control'));
						echo $this->Form->button('Create Product', array('type'=>'submit', 'class'=>'btn btn-default'));
						echo $this->Form->end();
						?>
					</div>
				</div>
				
			<?php //echo $this->element('sql_dump'); ?>
			
