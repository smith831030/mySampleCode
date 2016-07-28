			
			<div class="main">
				<?php echo $this->Session->flash('auth'); ?>
				<?php if($login){ ?>
					<h2>Psynergy INVOICE SYSTEM</h2>
				<?php }else{ ?>
					<?php echo $this->Form->create('Member', array('type'=>'post', 'class'=>'form-signin'));?>
					<label for="inputEmail" class="sr-only">Email address</label>
					<?php echo $this->Form->text('Member.mem_id', array('id'=>'inputEmail', 'class'=>'form-control', 'placeholder'=>'ID', 'required', 'autofocus'));?>
					<label for="inputPassword" class="sr-only">Password</label>
					<?php echo $this->Form->password('Member.mem_pwd', array('id'=>'inputPassword', 'class'=>'form-control', 'placeholder'=>'Password', 'required'));?>

					<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
					<?php echo $this->Form->end();?>
				<?php } ?>
			</div>
			<?php //echo $this->element('sql_dump'); ?>
			
