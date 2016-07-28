var main_pageUrl='/invoice';	//if site is under sub directory

function number_format( number )
{
 	var arrNum=String(number).split('.');
	var nArr = String(arrNum[0]).split('').join(',').split('');
	for( var i=nArr.length-1, j=1; i>=0; i--, j++)  if( j%6 != 0 && j%2 == 0) nArr[i] = '';
	
	if(arrNum.length>1)
		return nArr.join('')+'.'+arrNum[1];
	else
		return nArr.join('');
}


function delCompany(if_idx){
	if(confirm('Do you want to delete this Company information?')){
		location.href = main_pageUrl+'/Managers/Company/Delete/'+if_idx;
	}else{
		return false;
	}
}

function delBank(b_idx){
	if(confirm('Do you want to delete this bank?')){
		location.href = main_pageUrl+'/Managers/Bank/Delete/'+b_idx;
	}else{
		return false;
	}
}

function delCustomer(c_idx){
	if(confirm('Do you want to delete this customer?')){
		location.href = main_pageUrl+'/Managers/Customer/Delete/'+c_idx;
	}else{
		return false;
	}
}

function delProduct(pro_idx){
	if(confirm('Do you want to delete this product?')){
		location.href = main_pageUrl+'/Managers/Product/Delete/'+pro_idx;
	}else{
		return false;
	}
}

function delCategory(cate_idx){
	if(confirm('Do you want to delete this category?')){
		location.href = main_pageUrl+'/Managers/Category/Delete/'+cate_idx;
	}else{
		return false;
	}
}

function delInvoice(i_idx){
	if(confirm('Do you want to delete this invoice?')){
		location.href = main_pageUrl+'/Managers/Invoice/Delete/'+i_idx;
	}else{
		return false;
	}
}

function delPayable(ip_idx){
	if(confirm('Do you want to delete this payment?')){
		location.href = main_pageUrl+'/Managers/Payable/Delete/'+ip_idx;
	}else{
		return false;
	}
}

function toggleSection(group, obj){
	$('.'+group).toggle('fast'); 
	
	if($(obj).children().attr('class').indexOf('glyphicon-plus')>0){
		$(obj).children().removeClass('glyphicon-plus');
		$(obj).children().addClass('glyphicon-minus');
	}else{
		$(obj).children().removeClass('glyphicon-minus');
		$(obj).children().addClass('glyphicon-plus');
	}
}

function add_program(cate_idx, pro_idx, pro_name, pro_type, pro_price){
	var item=$('<li class="list-group-item product_list"></li>');	//li
	var div=$('<div class="form-group-sm"></div>');		//div
	
	//hidden fields
	$('<input type="hidden" name="data[IvInvoiceProduct][cate_idx][]" value="'+cate_idx+'" />').appendTo(div);
	$('<input type="hidden" name="data[IvInvoiceProduct][pro_idx][]" value="'+pro_idx+'" />').appendTo(div);
	$('<input type="hidden" name="data[IvInvoiceProduct][ip_name][]" value="'+pro_name+'" />').appendTo(div);
	$('<input type="hidden" name="data[IvInvoiceProduct][ip_price][]" class="ip_price" value="'+pro_price+'" />').appendTo(div);
	$('<input type="hidden" name="data[IvInvoiceProduct][ip_type][]" value="'+pro_type+'" />').appendTo(div);
	
	//product name & price
	var product=$('<div class="col-sm-6">'+pro_name+'</div>');
	$('<strong>$'+number_format(pro_price.toFixed(2))+'</strong>').appendTo(product);
	product.appendTo(div);
	
	//product type
	if(pro_type==1){	//ea
		$('<div class="col-sm-2 col-sm-offset-3"><input type="number" name="data[IvInvoiceProduct][ip_qty][]" id="ip_qty" class="form-control" placeholder="Ea" value="1" onchange="calc_price();" required="required" /></div>').appendTo(div);
		$('<input type="hidden" name="data[IvInvoiceProduct][ip_startdate][]" value="0000-00-00" />').appendTo(div);
	}else if(pro_type==2){	//per week
		var sub_div=$('<div class="col-sm-3"></div>');
		var startdate=$('<input type="text" name="data[IvInvoiceProduct][ip_startdate][]" class="form-control" placeholder="YYYY-MM-DD" required="required" />').datepicker({dateFormat:'yy-mm-dd'});
		startdate.appendTo(sub_div);
		sub_div.appendTo(div);
		$('<div class="col-sm-2"><input type="number" name="data[IvInvoiceProduct][ip_qty][]" id="ip_qty" class="form-control" placeholder="Week" onchange="calc_price();" required="required" /></div>').appendTo(div);
	}
	
	//delete button
	$('<div class="col-sm-1"><button class="btn btn-default btn-xs" onclick="remove_program(this);"><span class="glyphicon glyphicon glyphicon-minus"></span></button></div>').appendTo(div);
	div.appendTo(item);
	
	//clearfix
	$('<div class="clearfix"></div>').appendTo(item);
	item.appendTo($('.list_programs'));
	
	calc_price();
}

function add_program_new(){
	var li=$('<li class="list-group-item product_list" />');
	var div=$('<div class="form-group form-group-sm " />');
	/* First DIV */
	var div_first=$('<div class="col-sm-7" />');
	$('<input type="hidden" name="data[IvInvoiceProduct][cate_idx][]" value="0" />').appendTo(div_first);
	$('<input type="hidden" name="data[IvInvoiceProduct][pro_idx][]" value="0">').appendTo(div_first);
	
	var select=$('<select name="data[IvInvoiceProduct][ip_type][]" id="IvInvoiceProductIpType" class="form-control" onchange="setProductType(this); return false;" />');
	$('<option>Type</option>').appendTo(select);
	$('<option value="1">Ea</option>').appendTo(select);
	$('<option value="2">Per Week</option>').appendTo(select);
	select.appendTo(div_first);
	
	$('<input type="text" name="data[IvInvoiceProduct][ip_name][]" value="" placeholder="Product Name" class="form-control" />').appendTo(div_first);
	$('<input type="number" name="data[IvInvoiceProduct][ip_price][]" step="0.1" class="ip_price form-control" value="" onchange="calc_price();" placeholder="Price" />').appendTo(div_first);
	div.append(div_first);
	
	/* Second DIV */
	var div_second=$('<div class="col-sm-4 product_type" />');
	div.append(div_second);
	
	/* Third DIV */
	var div_third=$('<div class="col-sm-1" />');
	$('<button class="btn btn-default btn-xs" onclick="remove_program(this);"><span class="glyphicon glyphicon glyphicon-minus"></span></button>').appendTo(div_third);
	div.append(div_third);
	
	/* Last DIV */
	var div_last=$('<div class="clearfix"></div>');
	div.append(div_last);
	
	li.append(div);
	li.appendTo($('.list_programs'));
}

function remove_program(obj){
	$(obj).parent().parent().parent().remove();
	calc_price();
}

function calc_price(){
	var subtotal=0;
	var total=0;
	var gst=0;
	var discount=$('#i_discount').val();
	var gst_type=$('#i_gst_type').val();
	
	$('.product_list').each(function(){
		subtotal+=($(this).find('input.ip_price').val()*$(this).find('input#ip_qty').val());
	})
	subtotal-=discount;
	$('#span_subtotal').html('$'+number_format(subtotal.toFixed(2)));
	total=subtotal;
	
	if(gst_type==1 || gst_type==4){	//Non GST or Cash
		gst=0;
	}else if(gst_type==2){	//+GST
		gst=subtotal*0.1;
		total+=gst;
	}else if(gst_type==3){	//inc GST
		gst=subtotal/11;
	}
	
	
	$('#span_gst').html('$'+number_format(gst.toFixed(2)));
	$('#span_total').html('$'+number_format(total.toFixed(2)));
}

function calc_price_payable(){
	var subtotal=parseInt($('#IvPayableIpSubtotal').val());
	var total=0;
	var gst=0;
	var gst_type=$('#ip_gst_type').val();
		
	if(gst_type==1 || gst_type==4){	//Non GST or Cash
		gst=0;
		total=subtotal;
	}else if(gst_type==2){	//+GST
		gst=subtotal*0.1;
		total=subtotal+gst;
	}else if(gst_type==3){	//inc GST
		gst=subtotal/11;
		total=subtotal;
	}
	
	
	$('#span_gst').html('$'+number_format(gst.toFixed(2)));
	$('#span_total').html('$'+number_format(total.toFixed(2)));
}

function showPaymentChange(i_payment, i_idx){
	$('#section_payment_change').show();
	$('#payment_i_idx').val(i_idx)
	$('input.i_payment').each(function(){
		if($(this).val()==i_payment){
			$(this).prop('checked', true);
		}
	});

}

function save_excel(url){
	if(url.indexOf('index')<0)
		url=url+'/excel';
	else
		url=url.replace('index','excel');
	
	location.href=url;
}

function go_schedule(type){
	var year=$('#dateYear').val();
	var month=$('#dateMonth').val();
		
	if(type=='prev'){
		if(parseInt(month)==1){
			year = parseInt(year)-1;
			month = 12;
		}else{
			month = parseInt(month)-1;
			if(month<10)
				month='0'+month;
		}
	}else if(type=='next'){
		if(parseInt(month)==12){
			year = parseInt(year)+1;
			month = '01';
		}else{
			month = parseInt(month)+1;
			if(month<10)
				month='0'+month;
		}
	}
		
	location.href=main_pageUrl+'/Managers/Schedule/index/'+month+'/'+year+'#schedule';
}

function setCustomer(obj){
	if($(obj).val()==''){
		$('#IvInvoiceCustomerIcCompany').val('');
		$('#IvInvoiceCustomerIcAddress').val('');
		$('#IvInvoiceCustomerIcContact').val('');
		$('#IvInvoiceCustomerIcAttention').val('');
		$('#IvInvoiceCustomerIcEmail').val('');
	}else{
		$.getJSON(main_pageUrl+'/Managers/Customer/Detail/'+$(obj).val(), function(data){
			$('#IvInvoiceCustomerIcCompany').val(data['IvCustomer'].c_company);
			$('#IvInvoiceCustomerIcAddress').val(data['IvCustomer'].c_address);
			$('#IvInvoiceCustomerIcContact').val(data['IvCustomer'].c_contact);
			$('#IvInvoiceCustomerIcAttention').val(data['IvCustomer'].c_attention);
			$('#IvInvoiceCustomerIcEmail').val(data['IvCustomer'].c_email);
		});
	}
}

function checkCreateInvoice(){
	if($('#IvInvoiceCustomerCIdx').val()=='' && (
			$('#IvInvoiceCustomerIcCompany').val()=='' &&
			$('#IvInvoiceCustomerIcAddress').val()=='' &&
			$('#IvInvoiceCustomerIcContact').val()=='' &&
			$('#IvInvoiceCustomerIcAttention').val()=='' &&
			$('#IvInvoiceCustomerIcEmail').val()==''
		)){
			alert('You must input Customer information');
			$('#IvInvoiceCustomerCIdx').focus();
			return false;
	}
	return true;
}

function setProductType(obj){
	if($(obj).val()==1){ //Ea
		var start_date=$('<input type="hidden" name="data[IvInvoiceProduct][ip_startdate][]" value="0000-00-00" />');
		var ip_qty=$('<input type="number" name="data[IvInvoiceProduct][ip_qty][]" id="ip_qty" class="form-control" placeholder="Ea" onchange="calc_price();" required="required">');
		$(obj).parent().parent().children('.product_type').html(start_date);
		$(obj).parent().parent().children('.product_type').append(ip_qty);
	}else if($(obj).val()==2){	//Per Week
		var start_date=$('<input type="text" name="data[IvInvoiceProduct][ip_startdate][]" class="form-control" placeholder="YYYY-MM-DD" required="required">').datepicker({dateFormat:'yy-mm-dd'});
		var ip_qty=$('<input type="number" name="data[IvInvoiceProduct][ip_qty][]" id="ip_qty" class="form-control" placeholder="Week" onchange="calc_price();" required="required">');
		$(obj).parent().parent().children('.product_type').html(start_date);
		$(obj).parent().parent().children('.product_type').append(ip_qty);
	}else{
		$(obj).parent().parent().children('.product_type').html('');
	}
}