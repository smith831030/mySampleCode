<?php
/**
 * MainPageController
 *
 * @author		Smith.Seo(smith831030@gmail.com)
 * @created		16/05/2015
 *
 */
class ScheduleController extends AppController {
	public $helpers = array('Html', 'Form', 'Session', 'Text');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->set('mainPageUrl', '/invoice');
		$this->set('current_page', 'schedule');
		$this->layout = 'managers';
		$this->AdminOnly();
	}
	
	public function Managers_index($month=null, $year=null) {
		if(!empty($month) && !empty($year)){
			$this->set('month', $month);
			$this->set('year', $year);
			$this->request->data['date']['month']=$month;
			$this->request->data['date']['year']=$year;
		}else{
			$this->set('month', date('m'));
			$this->set('year', date('Y'));
		}
	}
	
	public function Managers_ScheduleList($year, $month){
		$this->AdminOnly();
		$this->layout='ajax';
		$this->loadModel('IvInvoice');
		
		$options=array(
			'fields'=>'IvInvoice.i_idx, IvInvoiceCustomer.ic_company, DATE_ADD(IvInvoiceProduct.ip_startdate, INTERVAL +(IvInvoiceProduct.ip_qty*7-1) day) AS ip_enddate',
			'joins'=>array(array(
					'table'=>'iv_invoice_products',
					'alias'=>'IvInvoiceProduct',
					'type'=>'INNER',
					'conditions'=>array('IvInvoice.i_idx=IvInvoiceProduct.i_idx')
				),array(
							'table'=>'iv_invoice_customers',
							'alias'=>'IvInvoiceCustomer',
							'type'=>'INNER',
							'conditions'=>array('IvInvoice.i_idx=IvInvoiceCustomer.i_idx')
				)
			),
			'conditions'=>array('IvInvoiceProduct.ip_type'=>2, 
								'IvInvoice.i_status'=>2,
								'DATE_ADD(IvInvoiceProduct.ip_startdate, INTERVAL +(IvInvoiceProduct.ip_qty*7-1) day) BETWEEN ? AND ?'=>array($year.'-'.$month.'-01 00:00:00', $year.'-'.$month.'-31 23:59:59')
							)
		);
		$schedule=$this->IvInvoice->find('all', $options, array('order'=>'IvInvoiceProduct.ip_startdate DESC'));
		$this->set(compact('schedule'));
	}
}
?>