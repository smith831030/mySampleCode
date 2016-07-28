<?php
/**
 * MainPageController
 *
 * @author		Smith.Seo(smith831030@gmail.com)
 * @created		16/05/2015
 *
 */
class InvoiceController extends AppController {
	public $helpers = array('Html', 'Form', 'Session', 'Text');
	public $components = array('Paginator');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->set('mainPageUrl', '/invoice');
		$this->set('current_page', 'invoice');
		$this->AdminOnly();
		$this->layout = 'managers';
	}
	
	public function Managers_index(){
		$this->loadModel('IvInvoice');
		$paginate = array(
			'fields'=>'IvInvoice.i_idx, IvInvoice.i_invoice_no, IvInvoice.created, IvInvoice.i_gst, IvInvoice.i_gst_type, IvInvoice.i_total_price, IvInvoice.i_status,
						IvInvoiceCustomer.ic_company',
			'joins'=>array(array(
							'table'=>'iv_invoice_customers',
							'alias'=>'IvInvoiceCustomer',
							'type'=>'INNER',
							'conditions'=>array('IvInvoice.i_idx=IvInvoiceCustomer.i_idx')
				)
			),
			'order'=>'IvInvoice.i_idx DESC',
			'conditions'=>array('IvInvoice.i_status > '=>0),
			'limit' => 10
		);
		
		$this->Paginator->settings = $paginate;
		$this->set('invoices', $this->Paginator->paginate('IvInvoice'));
	}
	
	public function Managers_AddNew(){
		if($this->request->data){
			$this->loadModel('IvInvoice');
			$this->loadModel('IvInformation');
			$this->loadModel('IvInvoiceInformation');
			$this->loadModel('IvCustomer');
			$this->loadModel('IvInvoiceCustomer');
			$this->loadModel('IvInvoiceProduct');
			
			//mem_idx
			$this->request->data['IvInvoice']['mem_idx']=$this->Auth->user('mem_idx');
			
			//invoice no
			$fields='IvInvoice.i_invoice_no';
			$conditions=array('IvInvoice.i_invoice_no LIKE '=>'HB'.date('y').'%');
			$last_invoice_no=$this->IvInvoice->find('first', array('fields'=>$fields, 'conditions'=>$conditions, 'order'=>'IvInvoice.i_idx DESC'));
			
			$invoice_no='HB'.date('y');
			if(empty($last_invoice_no))
				$invoice_no=$invoice_no.'00001';
			else
				$invoice_no=$invoice_no.str_pad(((int)substr($last_invoice_no['IvInvoice']['i_invoice_no'], -5,5))+1, 5, "0", STR_PAD_LEFT);
			
			$this->request->data['IvInvoice']['i_invoice_no']=$invoice_no;
			
			//calc price
			if(!empty($this->request->data['IvInvoiceProduct']['pro_idx'])){
				$subtotal=0;
				$total=0;
				for($i=0; $i<sizeof($this->request->data['IvInvoiceProduct']['pro_idx']); $i++){
					$subtotal+=($this->request->data['IvInvoiceProduct']['ip_price'][$i] * $this->request->data['IvInvoiceProduct']['ip_qty'][$i]);
				}
				$this->request->data['IvInvoice']['i_subtotal_price']=$subtotal;
				$subtotal-=empty($this->request->data['IvInvoice']['i_discount'])?0:$this->request->data['IvInvoice']['i_discount'];
				$total=$subtotal;
				
				if($this->request->data['IvInvoice']['i_gst_type']==1 || $this->request->data['IvInvoice']['i_gst_type']==4){
					$this->request->data['IvInvoice']['i_gst']=0;
				}elseif($this->request->data['IvInvoice']['i_gst_type']==2){
					$this->request->data['IvInvoice']['i_gst']=$subtotal*0.1;
					$total+=$this->request->data['IvInvoice']['i_gst'];
				}elseif($this->request->data['IvInvoice']['i_gst_type']==3){
					$this->request->data['IvInvoice']['i_gst']=$subtotal/11;
				}
				
				$this->request->data['IvInvoice']['i_total_price']=$total;
			}
			
			//invoice status
			$this->request->data['IvInvoice']['i_status']=1;
			
			//insert invoice
			if($this->IvInvoice->save($this->request->data)){
				//insert invoice information
				$invoice_information=$this->IvInformation->findByIfIdx($this->request->data['IvInvoiceInformation']['if_idx']);
				$this->request->data['IvInvoiceInformation']['i_idx']=$this->IvInvoice->id;
				$this->request->data['IvInvoiceInformation']['ii_abn']=$invoice_information['IvInformation']['if_abn'];
				$this->request->data['IvInvoiceInformation']['ii_address']=$invoice_information['IvInformation']['if_address'];
				$this->request->data['IvInvoiceInformation']['ii_company']=$invoice_information['IvInformation']['if_company'];
				$this->request->data['IvInvoiceInformation']['ii_contact']=$invoice_information['IvInformation']['if_contact'];
				$this->request->data['IvInvoiceInformation']['ii_email']=$invoice_information['IvInformation']['if_email'];
				$this->request->data['IvInvoiceInformation']['ii_logo']=$invoice_information['IvInformation']['if_logo'];
				$this->IvInvoiceInformation->save($this->request->data['IvInvoiceInformation']);
				
				//insert invoice customer
				if(empty($this->request->data['IvInvoiceCustomer']['c_idx']))
					$this->request->data['IvInvoiceCustomer']['c_idx']=0;
				$this->request->data['IvInvoiceCustomer']['i_idx']=$this->IvInvoice->id;
				$this->IvInvoiceCustomer->save($this->request->data['IvInvoiceCustomer']);
				
				//insert invoice product
				if(!empty($this->request->data['IvInvoiceProduct']['pro_idx'])){
					for($i=0; $i<sizeof($this->request->data['IvInvoiceProduct']['pro_idx']); $i++){
						$this->IvInvoiceProduct->save(array(
							'i_idx'=>$this->IvInvoice->id,
							'cate_idx'=>$this->request->data['IvInvoiceProduct']['cate_idx'][$i],
							'pro_idx'=>$this->request->data['IvInvoiceProduct']['pro_idx'][$i],
							'ip_startdate'=>$this->request->data['IvInvoiceProduct']['ip_startdate'][$i],
							'ip_qty'=>$this->request->data['IvInvoiceProduct']['ip_qty'][$i],
							'ip_name'=>$this->request->data['IvInvoiceProduct']['ip_name'][$i],
							'ip_price'=>$this->request->data['IvInvoiceProduct']['ip_price'][$i],
							'ip_type'=>$this->request->data['IvInvoiceProduct']['ip_type'][$i]
							)
						);
						$this->IvInvoiceProduct->clear();
					}
				}
				
				$this->Session->setFlash(__('Invoice has been created.'));
				return $this->redirect(array('action' => 'Managers_index'));
			}
		}else{
			//company
			$this->loadModel('IvInformation');
			$fields='IvInformation.if_idx, IvInformation.if_company, IvInformation.if_logo, IvInformation.if_abn, IvInformation.if_address, IvInformation.if_contact, IvInformation.if_email';
			$this->set('companies', $this->IvInformation->find('list', array('fields'=>$fields, 'order'=>'IvInformation.if_idx DESC')));
			
			//customer
			$this->loadModel('IvCustomer');
			$fields='IvCustomer.c_idx, IvCustomer.c_company, IvCustomer.c_address, IvCustomer.c_contact, IvCustomer.c_attention, IvCustomer.created';
			$this->set('customers', $this->IvCustomer->find('list', array('fields'=>$fields, 'order'=>'IvCustomer.c_company ASC')));
			
			//bank
			$this->loadModel('ItBank');
			$fields='ItBank.b_idx, ItBank.b_name, ItBank.b_acc_no, ItBank.b_acc_name, ItBank.b_bsb';
			$this->set('banks', $this->ItBank->find('list', array('fields'=>$fields, 'order'=>'ItBank.b_idx DESC')));
			
			//product
			$this->loadModel('IvProduct');
			$fields='IvProduct.pro_idx, IvProduct.pro_name, IvProduct.pro_price, IvProduct.pro_type, IvProduct.created, 
					IvCategory.cate_idx, IvCategory.cate_title';
			$joins=array(array(
					'table'=>'os_iv_categories',
					'alias'=>'IvCategory',
					'type'=>'INNER',
					'conditions'=>array('IvProduct.cate_idx=IvCategory.cate_idx')
				)
			);
			$this->set('products', $this->IvProduct->find('all', array('fields'=>$fields, 'joins'=>$joins, 'order'=>'IvCategory.cate_idx DESC, IvProduct.pro_idx DESC')));
		}
	}
	
	public function Managers_Detail($i_idx=null){
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
							'type'=>'LEFT OUTER',
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
		$this->set('detail', $detail);
		
		//product list
		$this->loadModel('IvInvoiceProduct');
		$options=array(
			'fields'=>'IvInvoiceProduct.ip_idx, IvInvoiceProduct.ip_startdate, IvInvoiceProduct.ip_qty, IvInvoiceProduct.ip_name, IvInvoiceProduct.ip_price, IvInvoiceProduct.ip_type,
						IvCategory.cate_title',
			'conditions'=>array('IvInvoiceProduct.i_idx'=>$i_idx),
			'joins'=>array(array(
							'table'=>'os_iv_categories',
							'alias'=>'IvCategory',
							'type'=>'LEFT OUTER',
							'conditions'=>array('IvInvoiceProduct.cate_idx=IvCategory.cate_idx')
						)
					),
			'order'=>'IvInvoiceProduct.ip_idx DESC'
		);
		$products=$this->IvInvoiceProduct->find('all', $options);
		$this->set('products', $products);
	}
	
	public function Managers_Modify($i_idx=null){
		if($this->request->data){
			$this->loadModel('IvInvoice');
			$this->loadModel('IvInformation');
			$this->loadModel('IvInvoiceInformation');
			$this->loadModel('IvCustomer');
			$this->loadModel('IvInvoiceCustomer');
			$this->loadModel('IvInvoiceProduct');
			
			//mem_idx
			$this->request->data['IvInvoice']['mem_idx']=$this->Auth->user('mem_idx');
			
			//calc price
			if(!empty($this->request->data['IvInvoiceProduct']['pro_idx'])){
				$subtotal=0;
				$total=0;
				for($i=0; $i<sizeof($this->request->data['IvInvoiceProduct']['pro_idx']); $i++){
					$subtotal+=($this->request->data['IvInvoiceProduct']['ip_price'][$i] * $this->request->data['IvInvoiceProduct']['ip_qty'][$i]);
				}
				$this->request->data['IvInvoice']['i_subtotal_price']=$subtotal;
				$subtotal-=empty($this->request->data['IvInvoice']['i_discount'])?0:$this->request->data['IvInvoice']['i_discount'];
				$total=$subtotal;
				
				if($this->request->data['IvInvoice']['i_gst_type']==1 || $this->request->data['IvInvoice']['i_gst_type']==4){
					$this->request->data['IvInvoice']['i_gst']=0;
				}elseif($this->request->data['IvInvoice']['i_gst_type']==2){
					$this->request->data['IvInvoice']['i_gst']=$subtotal*0.1;
					$total+=$this->request->data['IvInvoice']['i_gst'];
				}elseif($this->request->data['IvInvoice']['i_gst_type']==3){
					$this->request->data['IvInvoice']['i_gst']=$subtotal/11;
				}
				
				$this->request->data['IvInvoice']['i_total_price']=$total;
			}
			
			//invoice status
			$this->request->data['IvInvoice']['i_status']=1;
			
			//insert invoice
			if($this->IvInvoice->save($this->request->data)){
				//insert invoice information
				$invoice_information=$this->IvInformation->findByIfIdx($this->request->data['IvInvoiceInformation']['if_idx']);
				$this->request->data['IvInvoiceInformation']['i_idx']=$this->IvInvoice->id;
				$this->request->data['IvInvoiceInformation']['ii_abn']=$invoice_information['IvInformation']['if_abn'];
				$this->request->data['IvInvoiceInformation']['ii_address']=$invoice_information['IvInformation']['if_address'];
				$this->request->data['IvInvoiceInformation']['ii_company']=$invoice_information['IvInformation']['if_company'];
				$this->request->data['IvInvoiceInformation']['ii_contact']=$invoice_information['IvInformation']['if_contact'];
				$this->request->data['IvInvoiceInformation']['ii_email']=$invoice_information['IvInformation']['if_email'];
				$this->request->data['IvInvoiceInformation']['ii_logo']=$invoice_information['IvInformation']['if_logo'];
				$this->IvInvoiceInformation->save($this->request->data['IvInvoiceInformation']);
				
				//insert invoice customer
				if(empty($this->request->data['IvInvoiceCustomer']['c_idx']))
					$this->request->data['IvInvoiceCustomer']['c_idx']=0;
				$this->request->data['IvInvoiceCustomer']['i_idx']=$this->IvInvoice->id;
				$this->IvInvoiceCustomer->save($this->request->data['IvInvoiceCustomer']);
				/*
				$invoice_customer=$this->IvCustomer->findByCIdx($this->request->data['IvInvoiceCustomer']['c_idx']);
				$this->request->data['IvInvoiceCustomer']['i_idx']=$this->IvInvoice->id;
				$this->request->data['IvInvoiceCustomer']['ic_company']=$invoice_customer['IvCustomer']['c_company'];
				$this->request->data['IvInvoiceCustomer']['ic_address']=$invoice_customer['IvCustomer']['c_address'];
				$this->request->data['IvInvoiceCustomer']['ic_contact']=$invoice_customer['IvCustomer']['c_contact'];
				$this->request->data['IvInvoiceCustomer']['ic_attention']=$invoice_customer['IvCustomer']['c_attention'];
				$this->request->data['IvInvoiceCustomer']['ic_email']=$invoice_customer['IvCustomer']['c_email'];
				$this->IvInvoiceCustomer->save($this->request->data['IvInvoiceCustomer']);
				*/
				
				//insert invoice product
				if(!empty($this->request->data['IvInvoiceProduct']['pro_idx'])){
					if($this->IvInvoiceProduct->deleteAll(array('IvInvoiceProduct.i_idx'=>$this->IvInvoice->id), false)){
						for($i=0; $i<sizeof($this->request->data['IvInvoiceProduct']['pro_idx']); $i++){
							$this->IvInvoiceProduct->save(array(
								'i_idx'=>$this->IvInvoice->id,
								'cate_idx'=>$this->request->data['IvInvoiceProduct']['cate_idx'][$i],
								'pro_idx'=>$this->request->data['IvInvoiceProduct']['pro_idx'][$i],
								'ip_startdate'=>$this->request->data['IvInvoiceProduct']['ip_startdate'][$i],
								'ip_qty'=>$this->request->data['IvInvoiceProduct']['ip_qty'][$i],
								'ip_name'=>$this->request->data['IvInvoiceProduct']['ip_name'][$i],
								'ip_price'=>$this->request->data['IvInvoiceProduct']['ip_price'][$i],
								'ip_type'=>$this->request->data['IvInvoiceProduct']['ip_type'][$i]
								)
							);
							$this->IvInvoiceProduct->clear();
						}
					}
				}
				
				$this->Session->setFlash(__('Invoice has been created.'));
				return $this->redirect(array('action' => 'Managers_index'));
			}
		}else{
			$this->loadModel('IvInvoice');
			//invoice information
			$options=array(
				'fields'=>'IvInvoice.i_idx, IvInvoice.b_idx, IvInvoice.i_invoice_no, IvInvoice.i_payment_due, IvInvoice.i_subtotal_price, IvInvoice.i_total_price, 
							IvInvoice.i_gst, IvInvoice.i_gst_type, IvInvoice.i_discount, IvInvoice.i_comment, IvInvoice.i_status, IvInvoice.created,
							IvInvoiceInformation.ii_idx, IvInvoiceInformation.if_idx, 
							IvInvoiceCustomer.ic_idx, IvInvoiceCustomer.c_idx, IvInvoiceCustomer.ic_company, IvInvoiceCustomer.ic_address, IvInvoiceCustomer.ic_contact, IvInvoiceCustomer.ic_attention, IvInvoiceCustomer.ic_email
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
							)
						)
			);
			$detail=$this->IvInvoice->find('first', $options);
			$this->request->data=$detail;
			
			//product list
			$this->loadModel('IvInvoiceProduct');
			$options=array(
				'fields'=>'IvInvoiceProduct.ip_idx, IvInvoiceProduct.cate_idx, IvInvoiceProduct.pro_idx, IvInvoiceProduct.ip_startdate, IvInvoiceProduct.ip_qty, IvInvoiceProduct.ip_name, IvInvoiceProduct.ip_price, IvInvoiceProduct.ip_type',
				'conditions'=>array('IvInvoiceProduct.i_idx'=>$i_idx),
				'order'=>'IvInvoiceProduct.ip_idx DESC'
			);
			$invoice_products=$this->IvInvoiceProduct->find('all', $options);
			$this->set('invoice_products', $invoice_products);
			
			//company
			$this->loadModel('IvInformation');
			$fields='IvInformation.if_idx, IvInformation.if_company, IvInformation.if_logo, IvInformation.if_abn, IvInformation.if_address, IvInformation.if_contact, IvInformation.if_email';
			$this->set('companies', $this->IvInformation->find('list', array('fields'=>$fields, 'order'=>'IvInformation.if_idx DESC')));
			
			//customer
			$this->loadModel('IvCustomer');
			$fields='IvCustomer.c_idx, IvCustomer.c_company, IvCustomer.c_address, IvCustomer.c_contact, IvCustomer.c_attention, IvCustomer.created';
			$this->set('customers', $this->IvCustomer->find('list', array('fields'=>$fields, 'order'=>'IvCustomer.c_idx DESC')));
			
			//bank
			$this->loadModel('ItBank');
			$fields='ItBank.b_idx, ItBank.b_name, ItBank.b_acc_no, ItBank.b_acc_name, ItBank.b_bsb';
			$this->set('banks', $this->ItBank->find('list', array('fields'=>$fields, 'order'=>'ItBank.b_idx DESC')));
			
			//product
			$this->loadModel('IvProduct');
			$fields='IvProduct.pro_idx, IvProduct.pro_name, IvProduct.pro_price, IvProduct.pro_type, IvProduct.created, 
					IvCategory.cate_idx, IvCategory.cate_title';
			$joins=array(array(
					'table'=>'os_iv_categories',
					'alias'=>'IvCategory',
					'type'=>'INNER',
					'conditions'=>array('IvProduct.cate_idx=IvCategory.cate_idx')
				)
			);
			$this->set('products', $this->IvProduct->find('all', array('fields'=>$fields, 'joins'=>$joins, 'order'=>'IvCategory.cate_idx DESC, IvProduct.pro_idx DESC')));
		}
	}
	
	public function Managers_Delete($i_idx=null){
		$this->loadModel('IvInvoice');
		$this->IvInvoice->save(array(
				'i_idx'=>$i_idx,
				'i_status'=>-1
				)
			);
		
		$this->Session->setFlash(__('Product has been deleted.'));
		return $this->redirect(array('action' => 'Managers_index'));
	}
	
	public function Managers_ChangePayment(){
		$this->loadModel('IvInvoice');
		if($this->request->is('post')){
			if($this->request->data['IvInvoice']['i_status']==2)
				$this->request->data['IvInvoice']['i_payment_date']=date('Y-m-d H:i:s');
			$this->IvInvoice->save($this->request->data);
			
			$this->Session->setFlash(__('Invoice payment status has been updated.'));
			return $this->redirect(array('action' => 'Managers_index'));
		}
	}
}
?>