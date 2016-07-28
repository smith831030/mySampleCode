
				<?php echo $this->Session->flash(); ?>
				<h2>Statistics > Detail</h2>
				<?php 
				if($invoice_type=='HB')
					include('managers__detail_hojubada.ctp');
				elseif($invoice_type=='ED')
					include('managers__detail_shop.ctp');
				elseif($invoice_type='IT' || $invoice_type=='ST' || $invoice_type=='IS')
					include('managers__detail_internship.ctp');
				?>