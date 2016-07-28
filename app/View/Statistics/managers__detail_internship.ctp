<?php
//Price setting
$subtotal = $detail['ItPayment']['p_subtotal_price'];
$subtotal -= $detail['ItPayment']['p_discount'];
$gst=0;
if($detail['ItPayment']['p_gst_type']==1){	//Non GST
	$gst_txt = 'INVOICE';
	$gst_type = 'Non GST';
}elseif($detail['ItPayment']['p_gst_type']==2){	//+GST
	$gst_txt = 'TAX INVOICE';
	$gst_type = '+GST';
	$gst = $subtotal*0.1;
}elseif($detail['ItPayment']['p_gst_type']==3){	//inc GST
	$gst_txt = 'TAX INVOICE';
	$gst_type = 'inc GST';
	$gst = $subtotal/11;
}else{	//Cash
	$gst_txt = 'INVOICE';
	$gst_type = 'Cash';
}
?>
<div class="invoice container">
	<div class="row">
		<div class="col-xs-6">
			<?php echo $this->Html->image('common/logo.png', array('class'=>'hidden_print_logo'));?>
		</div>
		<div class="col-xs-6 text-right">
			<br /><br />
			<strong><?php echo $gst_txt;?></strong>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-6">
			<address>
				<strong>ABN</strong> <span><?php echo $detail['IvInformation']['IvInformation']['if_abn'];?></span><br />
				<strong>Address</strong> <span><?php echo $detail['IvInformation']['IvInformation']['if_address'];?></span><br />
				<strong>Contact</strong> <span><?php echo $detail['IvInformation']['IvInformation']['if_contact'];?></span><br />
				<strong>Date</strong> <span><?php echo $detail['ItStudent']['created'];?></span><br />
				<strong>Invoice No</strong> <span><?php echo $detail['ItPayment']['p_invoice_no'];?></span>
			</address>
		</div>
		<div class="col-xs-6">
			<address>
				<strong>Bill To</strong> <span><?php echo $detail['ItAgent']['a_name'];?></span><br />
				<strong>Address</strong> <span><?php echo $detail['ItAgent']['a_address'].', '.$detail['ItAgent']['a_country'];?></span><br />
				<strong>Contact</strong> <span><?php echo $detail['ItAgent']['a_contact'];?></span>
			</address>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-12 table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th>Student Name</th>
						<th>Description</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo $detail['ItStudent']['s_first_name'].', '.$detail['ItStudent']['s_last_name'];?></td>
						<td>
						<?php 
						if(!empty($detail['ItStudentProgram']['sp_idx']))
							echo $detail['ItProgram']['p_title'].'<br />';
						if(!empty($detail['ItStudentSettlement']['ss_idx']))
							echo $detail['ItSettlement']['sm_title'];
						?>
						</td>
						<td>
						<?php
						if(!empty($detail['ItStudentProgram']['sp_idx']))
							echo '$'.number_format($detail['ItStudentProgram']['sp_program_price'], 2).'<br />';
						if(!empty($detail['ItStudentSettlement']['ss_idx']))
							echo '$'.number_format($detail['ItStudentSettlement']['ss_settlement_price'], 2);
						?>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="2" class="text-right">Discount</th>
						<td>$<?php echo number_format($detail['ItPayment']['p_discount'], 2);?></td>
					</tr>
					<tr>
						<th colspan="2" class="text-right">Sub Total</th>
						<td>$<?php echo number_format($subtotal, 2);?></td>
					</tr>
					<tr>
						<th colspan="2" class="text-right">GST(<?php echo $gst_type;?>)</th>
						<td>$<?php echo number_format($gst, 2);?></td>
					</tr>
					<tr>
						<th colspan="2" class="text-right">Total</th>
						<td>$<?php echo number_format($detail['ItPayment']['p_total_price'], 2);?></td>
					</tr>
				</tfoot>
			</table>
			
			<h2>Payment Details</h2>
			<p>
				<strong>Bank Name</strong> <span><?php echo $detail['ItBank']['b_name'];?></span><br />
				<strong>Account Name</strong> <span><?php echo $detail['ItBank']['b_acc_name'];?></span><br />
				<strong>Account Number</strong> <span><?php echo $detail['ItBank']['b_acc_no'];?></span><br />
				<?php
				if(!empty($detail['ItBank']['b_address'])){
					echo '<strong>Swift Code</strong> <span>'.$detail['ItBank']['b_bsb'].'</span><br />';
					echo '<strong>Bank Address</strong> <span>'.$detail['ItBank']['b_address'].'</span>';
				}else{
					echo '<strong>BSB</strong> <span>'.$detail['ItBank']['b_bsb'].'</span>';
				}
				?>
			</p>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 text-right">
			<button type="button" class="btn btn-primary btn-lg" onclick="window.print();">PRINT</button>
			<button type="button" class="btn btn-default btn-lg" onclick="location.href='<?php echo $mainPageUrl;?>/Managers/Statistics';">LIST</button>
		</div>
	</div>
</div>
<?php //echo $this->element('sql_dump'); ?>