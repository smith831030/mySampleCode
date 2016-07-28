<?php
/**
 * MainPageController
 *
 * @author		Smith.Seo(smith831030@gmail.com)
 * @created		16/05/2015
 *
 */
class PayableController extends AppController {
	public $helpers = array('Html', 'Form', 'Session', 'Text');
	public $components = array('Paginator');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->set('mainPageUrl', '/invoice');
		$this->set('current_page', 'payable');
		$this->AdminOnly();
		$this->layout = 'managers';
	}
	
	public function Managers_index(){
		$this->loadModel('IvPayable');
		$paginate = array(
			'fields'=>'IvPayable.ip_idx, IvPayable.ip_invoice_from, IvPayable.ip_gst, IvPayable.ip_gst_type, IvPayable.ip_total, IvPayable.ip_status, IvPayable.created',
			'order'=>'IvPayable.ip_idx DESC',
			'conditions'=>array('IvPayable.ip_status > '=>0),
			'limit' => 10
		);
		
		$this->Paginator->settings = $paginate;
		$this->set('payables', $this->Paginator->paginate('IvPayable'));
	}
	
	public function Managers_addNew(){
		if($this->request->is('post')){
			$this->loadModel('IvPayable');
			
			$this->request->data['IvPayable']['ip_status']=1;
			if($this->request->data['IvPayable']['ip_gst_type']==1 || $this->request->data['IvPayable']['ip_gst_type']==4){
				$this->request->data['IvPayable']['ip_gst']=0;
				$this->request->data['IvPayable']['ip_total']=$this->request->data['IvPayable']['ip_subtotal'];
			}else if($this->request->data['IvPayable']['ip_gst_type']==2){	//+GST
				$this->request->data['IvPayable']['ip_gst']=$this->request->data['IvPayable']['ip_subtotal']*0.1;
				$this->request->data['IvPayable']['ip_total']=$this->request->data['IvPayable']['ip_subtotal']+$this->request->data['IvPayable']['ip_gst'];
			}else if($this->request->data['IvPayable']['ip_gst_type']==3){	//inc GST
				$this->request->data['IvPayable']['ip_gst']=$this->request->data['IvPayable']['ip_subtotal']/11;
				$this->request->data['IvPayable']['ip_total']=$this->request->data['IvPayable']['ip_subtotal'];
			}
			
			if($this->IvPayable->save($this->request->data)){
				$this->Session->setFlash(__('Payable has been saved.'));
				return $this->redirect(array('action' => 'Managers_index'));
			}
		}
	}
	
	public function Managers_modify($ip_idx){
		$this->loadModel('IvPayable');
		
		if($this->request->data){
			$this->request->data['IvPayable']['ip_status']=1;
			if($this->request->data['IvPayable']['ip_gst_type']==1 || $this->request->data['IvPayable']['ip_gst_type']==4){
				$this->request->data['IvPayable']['ip_gst']=0;
				$this->request->data['IvPayable']['ip_total']=$this->request->data['IvPayable']['ip_subtotal'];
			}else if($this->request->data['IvPayable']['ip_gst_type']==2){	//+GST
				$this->request->data['IvPayable']['ip_gst']=$this->request->data['IvPayable']['ip_subtotal']*0.1;
				$this->request->data['IvPayable']['ip_total']=$this->request->data['IvPayable']['ip_subtotal']+$this->request->data['IvPayable']['ip_gst'];
			}else if($this->request->data['IvPayable']['ip_gst_type']==3){	//inc GST
				$this->request->data['IvPayable']['ip_gst']=$this->request->data['IvPayable']['ip_subtotal']/11;
				$this->request->data['IvPayable']['ip_total']=$this->request->data['IvPayable']['ip_subtotal'];
			}
			
			if($this->IvPayable->save($this->request->data)){
				$this->Session->setFlash(__('Payable has been updated.'));
				return $this->redirect(array('action' => 'Managers_index'));
			}
		}else{
			$detail=$this->IvPayable->findByIpIdx($ip_idx);
			$this->request->data=$detail;
		}
	}
	
	public function Managers_detail($ip_idx){
		$this->loadModel('IvPayable');
		
		$detail=$this->IvPayable->findByIpIdx($ip_idx);
		$this->set('detail', $detail);
	}
	
	public function Managers_Delete($ip_idx=null){
		$this->loadModel('IvPayable');
		$this->IvPayable->save(array(
				'ip_idx'=>$ip_idx,
				'ip_status'=>-1
				)
			);
		
		$this->Session->setFlash(__('Payable has been deleted.'));
		return $this->redirect(array('action' => 'Managers_index'));
	}
	
	public function Managers_ChangePayment(){
		$this->loadModel('IvPayable');
		if($this->request->is('post')){
			$this->IvPayable->save($this->request->data);
			
			$this->Session->setFlash(__('Invoice payment status has been updated.'));
			return $this->redirect(array('action' => 'Managers_index'));
		}
	}
}
?>