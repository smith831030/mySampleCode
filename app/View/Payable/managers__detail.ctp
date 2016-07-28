
				<?php echo $this->Session->flash(); ?>
				<h2>Payable > Detail</h2>
				<?php
				if($detail['IvPayable']['ip_gst_type']==1){	//Non GST
					$gst_txt = 'INVOICE';
					$gst_type = 'Non GST';
				}elseif($detail['IvPayable']['ip_gst_type']==2){	//+GST
					$gst_txt = 'TAX INVOICE';
					$gst_type = '+GST';
				}elseif($detail['IvPayable']['ip_gst_type']==3){	//inc GST
					$gst_txt = 'TAX INVOICE';
					$gst_type = 'inc GST';
				}else{	//Cash
					$gst_txt = '';
					$gst_type = 'Cash';
				}
				?>
				<div class="col-md-6">
					<p><label>Invoice From</label> <span><?php echo $detail['IvPayable']['ip_invoice_from'];?></span></p>
					<p><label>Invoice No</label> <span><a href="/internship/Managers/Student/Link/<?php echo $detail['IvPayable']['ip_invoice_no'];?>"><?php echo $detail['IvPayable']['ip_invoice_no'];?></a></span></p>
					<p><label>Due Date</label> <span><?php echo $detail['IvPayable']['ip_due_date'];?></span></p>
					<p><label>Sub Total</label> <span>$<?php echo number_format($detail['IvPayable']['ip_subtotal'],2);?></span></p>
					<p><label>GST Type</label> <span><?php echo $gst_type;?></span></p>
					<p><label>GST</label> <span><?php echo number_format($detail['IvPayable']['ip_gst'],2);?></span></p>
					<p><label>Total</label> <span><?php echo number_format($detail['IvPayable']['ip_total'],2);?></span></p>
					<p><label>Comment</label> <span><?php echo nl2br($detail['IvPayable']['ip_comment']);?></span></p>
					
					<button type="button" class="btn btn-default btn-lg" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Payable/Modify/<?php echo $detail['IvPayable']['ip_idx'];?>';">MODIFY</button>
					<button type="button" class="btn btn-default btn-lg" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Payable';">LIST</button>
				</div>
				<?php //echo $this->element('sql_dump'); ?>