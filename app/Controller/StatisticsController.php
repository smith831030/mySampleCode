<?php
/**
 * MainPageController
 *
 * @author		Smith.Seo(smith831030@gmail.com)
 * @created		16/05/2015
 *
 */
class StatisticsController extends AppController {
	public $helpers = array('Html', 'Form', 'Session', 'Text');
	public $components = array('Paginator');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->set('mainPageUrl', '/invoice');
		$this->set('current_page', 'statistics');
		$this->AdminOnly();

		$this->layout = 'managers';
	}
	
	public function Managers_index(){
		if($this->request->query){
			$startDate=$this->request->query['startDate'];
			$endDate=$this->request->query['endDate'];
			$this->set('startDate', $startDate);
			$this->set('endDate', $endDate);
		}
		
		$this->loadModel('AllInvoice');
		//list of invoice to 
		$options = array(
			'fields'=>'AllInvoice.idx, AllInvoice.invoice_to',
			'order'=>'AllInvoice.invoice_to ASC',
			'group'=>'AllInvoice.invoice_to'
		);
		$this->set('invoice_to', $this->AllInvoice->find('all', $options));
		
		$conditions=array();
		if(!empty($this->request->query['startDate']))
			$conditions[]=array('AllInvoice.created BETWEEN ? AND ?'=>array($startDate.' 00:00:00', $endDate.' 23:59:59'));
		
		if(!empty($this->params['named']['invoice_to']))
			$conditions[]=array('AllInvoice.invoice_to LIKE '=>'%'.urldecode($this->params['named']['invoice_to']).'%');
		
		if(!empty($this->params['named']['invoice_type']))
			$conditions[]=array('AllInvoice.invoice_type'=>$this->params['named']['invoice_type']);
		
		if(!empty($this->params['named']['gst_type']) && $this->params['named']['gst_type']>0)
			$conditions[]=array('AllInvoice.gst_type'=>$this->params['named']['gst_type']);
		
		if(!empty($this->params['named']['payment']) && $this->params['named']['payment']>0){
			if($this->params['named']['payment']==1){	//Unpaid
				$conditions[]=array('OR'=>array(
											array(
												'AllInvoice.status'=>1,
												'AllInvoice.invoice_type <> '=>'ED'
											),array(
												'AllInvoice.status <> '=>5,
												'AllInvoice.invoice_type'=>'ED'
											)
											
									)
								);
			}elseif($this->params['named']['payment']==2){	//Paid
				$conditions[]=array('OR'=>array(
											array(
												'AllInvoice.status'=>2,
												'AllInvoice.invoice_type <> '=>'ED'
											),array(
												'AllInvoice.status'=>5,
												'AllInvoice.invoice_type'=>'ED'
											)
											
									)
								);
			}
		}
		
		$paginate = array(
			'fields'=>'AllInvoice.idx, AllInvoice.invoice_no, AllInvoice.created, AllInvoice.gst_type, AllInvoice.total_price, AllInvoice.payment_date, AllInvoice.status, AllInvoice.bank, AllInvoice.invoice_type, AllInvoice.invoice_to',
			'conditions'=>$conditions,
			'order'=>'AllInvoice.created DESC',
			'limit' => 10
		);
		
		$this->Paginator->settings = $paginate;
		$this->set('invoices', $this->Paginator->paginate('AllInvoice'));
		
		//set total
		$total = $this->request->params['paging']['AllInvoice']['count'];
		$this->set('total', $total);
	}
	
	public function Managers_excel(){
		$this->layout='excel';
		
		if($this->request->query){
			$startDate=$this->request->query['startDate'];
			$endDate=$this->request->query['endDate'];
			$this->set('startDate', $startDate);
			$this->set('endDate', $endDate);
		}
		
		$this->loadModel('AllInvoice');
		$conditions=array();
		if(!empty($this->request->query['startDate']))
			$conditions[]=array('AllInvoice.created BETWEEN ? AND ?'=>array($startDate.' 00:00:00', $endDate.' 23:59:59'));
		
		if(!empty($this->params['named']['gst_type']) && $this->params['named']['gst_type']>0)
			$conditions[]=array('AllInvoice.gst_type'=>$this->params['named']['gst_type']);
		
		if(!empty($this->params['named']['payment']) && $this->params['named']['payment']>0){
			if($this->params['named']['payment']==1){	//Unpaid
				$conditions[]=array('OR'=>array(
											array(
												'AllInvoice.status'=>1,
												'AllInvoice.invoice_type <> '=>'ED'
											),array(
												'AllInvoice.status <> '=>5,
												'AllInvoice.invoice_type'=>'ED'
											)
											
									)
								);
			}elseif($this->params['named']['payment']==2){	//Paid
				$conditions[]=array('OR'=>array(
											array(
												'AllInvoice.status'=>2,
												'AllInvoice.invoice_type <> '=>'ED'
											),array(
												'AllInvoice.status'=>5,
												'AllInvoice.invoice_type'=>'ED'
											)
											
									)
								);
			}
		}
		
		$options = array(
			'fields'=>'AllInvoice.idx, AllInvoice.invoice_no, AllInvoice.created, AllInvoice.gst_type, AllInvoice.total_price, AllInvoice.payment_date, AllInvoice.status, AllInvoice.bank, AllInvoice.invoice_type, AllInvoice.invoice_to',
			'conditions'=>$conditions,
			'order'=>'AllInvoice.created DESC'
		);
		
		
		$this->set('invoices', $this->AllInvoice->find('all', $options));
		
		//set total
		$total = $this->AllInvoice->find('count', array('conditions'=>$conditions));
		$this->set('total', $total);
	}
	
	public function Managers_Detail($invoice_type, $idx){
		$this->set('invoice_type', $invoice_type);
		
		if($invoice_type=='HB'){
			$this->set('detail', $this->GetInvoiceDetailHojubada($idx));
		}elseif($invoice_type=='ED'){
			$this->set('detail', $this->GetInvoiceDetailShop($idx));
		}elseif($invoice_type='IT' || $invoice_type=='ST' || $invoice_type=='IS'){
			$this->set('detail', $this->GetInvoiceDetailInternship($idx));
		}
	}
	
	private function GetInvoiceDetailHojubada($i_idx){
		//invoice information
		$this->loadModel('IvInvoice');
		$options=array(
			'fields'=>'IvInvoice.i_idx, IvInvoice.i_invoice_no, IvInvoice.i_payment_due, IvInvoice.i_subtotal_price, IvInvoice.i_total_price, 
						IvInvoice.i_gst, IvInvoice.i_gst_type, IvInvoice.i_discount, IvInvoice.i_comment, IvInvoice.i_status, IvInvoice.created,
						IvInvoiceInformation.ii_abn, IvInvoiceInformation.ii_address, IvInvoiceInformation.ii_company, IvInvoiceInformation.ii_contact, IvInvoiceInformation.ii_email, IvInvoiceInformation.ii_logo,
						IvInvoiceCustomer.ic_company, IvInvoiceCustomer.ic_address, IvInvoiceCustomer.ic_contact, IvInvoiceCustomer.ic_attention,
						ItBank.b_name, ItBank.b_acc_no, ItBank.b_acc_name, ItBank.b_bsb, ItBank.b_address
						',
			'conditions'=>array('IvInvoice.i_idx'=>$i_idx),
			'joins'=>array(array(
							'table'=>'os_iv_invoice_informations',
							'alias'=>'IvInvoiceInformation',
							'type'=>'INNER',
							'conditions'=>array('IvInvoice.i_idx=IvInvoiceInformation.i_idx')
						),array(
							'table'=>'os_iv_invoice_customers',
							'alias'=>'IvInvoiceCustomer',
							'type'=>'INNER',
							'conditions'=>array('IvInvoice.i_idx=IvInvoiceCustomer.i_idx')
						),array(
							'table'=>'os_it_banks',
							'alias'=>'ItBank',
							'type'=>'INNER',
							'conditions'=>array('IvInvoice.b_idx=ItBank.b_idx')
						)
					)
		);
		$detail=$this->IvInvoice->find('first', $options);
		
		//product list
		$this->loadModel('IvInvoiceProduct');
		$options=array(
			'fields'=>'IvInvoiceProduct.ip_idx, IvInvoiceProduct.ip_startdate, IvInvoiceProduct.ip_qty, IvInvoiceProduct.ip_name, IvInvoiceProduct.ip_price, IvInvoiceProduct.ip_type,
						IvCategory.cate_title',
			'conditions'=>array('IvInvoiceProduct.i_idx'=>$i_idx),
			'joins'=>array(array(
							'table'=>'os_iv_categories',
							'alias'=>'IvCategory',
							'type'=>'INNER',
							'conditions'=>array('IvInvoiceProduct.cate_idx=IvCategory.cate_idx')
						)
					),
			'order'=>'IvInvoiceProduct.ip_idx DESC'
		);
		$detail['IvInvoiceProduct']=$this->IvInvoiceProduct->find('all', $options);
		
		return $detail;
	}
	
	private function GetInvoiceDetailInternship($s_idx){
		$this->loadModel('ItStudent');

		//Student information
		$fields='ItStudent.s_idx, ItStudent.a_idx, ItStudent.s_first_name, ItStudent.s_last_name, ItStudent.s_dob, ItStudent.s_nationality, ItStudent.s_gender, ItStudent.s_visa_type, ItStudent.s_visa_expired, ItStudent.s_email, ItStudent.s_mobile, ItStudent.s_status, ItStudent.sm_status, ItStudent.s_tfn, ItStudent.created,
				ItStudentAdinfo.sa_idx, ItStudentAdinfo.s_address, ItStudentAdinfo.s_country,
				ItAgent.a_name, ItAgent.a_country, ItAgent.a_address, ItAgent.a_contact,
				ItPayment.p_idx, ItPayment.b_idx, ItPayment.p_invoice_no, ItPayment.p_subtotal_price, ItPayment.p_discount, ItPayment.p_gst_type, ItPayment.p_total_price,
				ItBank.b_name, ItBank.b_acc_no, ItBank.b_acc_name, ItBank.b_bsb, ItBank.b_address,
				ItStudentProgram.sp_idx,  ItStudentProgram.pp_idx, ItStudentProgram.sp_program_price, ItStudentProgram.sp_arrival_city, ItStudentProgram.sp_arrival_date, ItStudentProgram.sp_preferred_city, ItStudentProgram.sp_preferred_starting_date, ItStudentProgram.sp_preferred_type_of_position, ItStudentProgram.sp_comment,
				ItProgramPrice.pp_price, ItProgram.p_title,
				ItStudentSettlement.ss_idx, ItStudentSettlement.sm_idx, ItStudentSettlement.smp_idx, ItStudentSettlement.ss_settlement_price, ItStudentSettlement.ss_arrival_city, ItStudentSettlement.ss_arrival_date, ItStudentSettlement.ss_airline, ItStudentSettlement.ss_flight_no, ItStudentSettlement.ss_messenger, ItStudentSettlement.ss_comment,
				ItSettlementPrice.smp_price, ItSettlement.sm_title
			';
		$joins=array(array(
					'table'=>'it_student_adinfos',
					'alias'=>'ItStudentAdinfo',
					'type'=>'LEFT OUTER',
					'conditions'=>array('ItStudent.s_idx=ItStudentAdinfo.s_idx')
				),array(
					'table'=>'it_agents',
					'alias'=>'ItAgent',
					'type'=>'LEFT OUTER',
					'conditions'=>array('ItStudent.a_idx=ItAgent.a_idx')
				),array(
					'table'=>'it_payments',
					'alias'=>'ItPayment',
					'type'=>'LEFT OUTER',
					'conditions'=>array('ItStudent.s_idx=ItPayment.s_idx')
				),array(
					'table'=>'it_banks',
					'alias'=>'ItBank',
					'type'=>'LEFT OUTER',
					'conditions'=>array('ItPayment.b_idx=ItBank.b_idx')
				),array(
					'table'=>'it_student_programs',
					'alias'=>'ItStudentProgram',
					'type'=>'LEFT OUTER',
					'conditions'=>array('ItStudent.s_idx=ItStudentProgram.s_idx')
				),array(
					'table'=>'it_program_prices',
					'alias'=>'ItProgramPrice',
					'type'=>'LEFT OUTER',
					'conditions'=>array('ItProgramPrice.pp_idx=ItStudentProgram.pp_idx')
				),array(
					'table'=>'it_programs',
					'alias'=>'ItProgram',
					'type'=>'LEFT OUTER',
					'conditions'=>array('ItProgramPrice.p_idx=ItProgram.p_idx')
				),array(
					'table'=>'it_student_settlements',
					'alias'=>'ItStudentSettlement',
					'type'=>'LEFT OUTER',
					'conditions'=>array('ItStudent.s_idx=ItStudentSettlement.s_idx')
				),array(
					'table'=>'it_settlement_prices',
					'alias'=>'ItSettlementPrice',
					'type'=>'LEFT OUTER',
					'conditions'=>array('ItSettlementPrice.smp_idx=ItStudentSettlement.smp_idx')
				),array(
					'table'=>'it_settlements',
					'alias'=>'ItSettlement',
					'type'=>'LEFT OUTER',
					'conditions'=>array('ItSettlementPrice.sm_idx=ItSettlement.sm_idx')
				)
		);
		$conditions=array('ItStudent.s_idx'=>$s_idx);
		$student=$this->ItStudent->find('first', array('fields'=>$fields, 'joins'=>$joins, 'conditions'=>$conditions));
		
		//Application confirmed date = Invoice date
		$this->loadModel('ItMemo');
		$fields='ItMemo.created';
		$conditions=array('ItMemo.s_idx'=>$s_idx, 'ItMemo.m_type'=>'Application confirmed');
		$invoice_date=$this->ItMemo->find('first', array('fields'=>$fields, 'conditions'=>$conditions));
		if(!empty($invoice_date))
			$student['ItStudent']['created']=$invoice_date['ItMemo']['created'];
		
		//invoice information
		$this->loadModel('IvInformation');
		$student['IvInformation']=$this->IvInformation->findByIfIdx(1);
		
		if($student['ItStudent']['s_status']>=1 || $student['ItStudent']['sm_status']>=1){
			return $student;
		}else{
			return -1;
		}
	}
	
	private function GetInvoiceDetailShop($order_idx){
		$this->loadModel('Order');
		//order details
		if($order_idx){
			$fields='Order.order_status, Order.order_code, Order.order_discount, Order.order_gst_type, Order.admin_title, Order.admin_abn, Order.admin_address, Order.admin_contact, Order.order_comment,
						OrderProduct.op_idx, OrderProduct.op_order_qty, OrderProduct.op_release_qty, OrderProduct.pro_bottle_per_box, OrderProduct.pro_price_per_bottle, OrderProduct.op_comment, 
						P.pro_name, P.pro_unit, P.pro_itemcode, C.cate_title, Shop.shop_title, Shop.shop_address, Shop.shop_contact';
			$join=array(array('table' => 'shops',
								'alias' => 'Shop',
								'type' => 'INNER',
								'conditions' => array('Order.shop_idx = Shop.shop_idx')
						),array('table' => 'order_products',
								'alias' => 'OrderProduct',
								'type' => 'INNER',
								'conditions' => array('Order.order_idx = OrderProduct.order_idx')
						),array('table' => 'products',
								'alias' => 'P',
								'type' => 'INNER',
								'conditions' => array('OrderProduct.pro_idx = P.pro_idx')
						),array('table' => 'categories',
								'alias' => 'C',
								'type' => 'INNER',
								'conditions' => array('P.cate_idx = C.cate_idx')
						)
					);
			$main_conditions = array('Order.order_idx' => $order_idx, 'OrderProduct.op_type'=>1);
			$service_conditions = array('Order.order_idx' => $order_idx, 'OrderProduct.op_type'=>0);
			
			$detailLists=$this->Order->find('all', array('fields'=>$fields, 'joins'=>$join, 'conditions' => $main_conditions));
			$detailLists_service=$this->Order->find('all', array('fields'=>$fields, 'joins'=>$join, 'conditions' => $service_conditions));
			
			$detail['OrderList']=$detailLists;
			$detail['OrderListService']=$detailLists_service;
			return $detail;
		}else{
			return -1;
		}
	}
}
?>