<?php
/**
 * MainPageController
 *
 * @author		Smith.Seo(smith831030@gmail.com)
 * @created		16/05/2015
 *
 */
class CustomerController extends AppController {
	public $helpers = array('Html', 'Form', 'Session', 'Text');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->set('mainPageUrl', '/invoice');
		$this->set('current_page', 'customer');
		$this->AdminOnly();
		$this->layout = 'managers';
	}
	
	public function Managers_index(){
		//customer list
		$this->loadModel('IvCustomer');
		$fields='IvCustomer.c_idx, IvCustomer.c_company, IvCustomer.c_description, IvCustomer.c_address, IvCustomer.c_contact, IvCustomer.c_attention, IvCustomer.created';
		$this->set('customers', $this->IvCustomer->find('all', array('fields'=>$fields, 'order'=>'IvCustomer.c_company ASC')));
	}
	
	public function Managers_AddNew(){
		if($this->request->data){
			$this->loadModel('IvCustomer');
			if($this->IvCustomer->save($this->request->data)){
				$this->Session->setFlash(__('Customer has been saved.'));
				return $this->redirect(array('action' => 'Managers_index'));
			}
		}
	}
	
	public function Managers_Detail($c_idx=null){
		$this->layout='ajax';
		$this->loadModel('IvCustomer');
		$result = $this->IvCustomer->findByCIdx($c_idx);
		$this->set(compact('result'));
	}
	
	public function Managers_Modify($c_idx=null){
		$this->loadModel('IvCustomer');
		$post = $this->IvCustomer->findByCIdx($c_idx);
		
		if($this->request->data){
			if($this->IvCustomer->save($this->request->data)){
				$this->Session->setFlash(__('Customer has been updated.'));
				return $this->redirect(array('action' => 'Managers_index'));
			}
		}else{
			$this->request->data = $post;
		}
	}
	
	public function Managers_Delete($c_idx=null){
		$this->loadModel('IvCustomer');
		$this->IvCustomer->delete($c_idx);
		
		$this->Session->setFlash(__('Customer has been deleted.'));
		return $this->redirect(array('action' => 'Managers_index'));
	}
}
?>