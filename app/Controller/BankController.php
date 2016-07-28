<?php
/**
 * MainPageController
 *
 * @author		Smith.Seo(smith831030@gmail.com)
 * @created		16/05/2015
 *
 */
class BankController extends AppController {
	public $helpers = array('Html', 'Form', 'Session', 'Text');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->set('mainPageUrl', '/invoice');
		$this->set('current_page', 'bank');
		$this->AdminOnly();
		$this->layout = 'managers';
	}
	
	public function Managers_index(){
		$this->set('current_page', 'bank');
		
		//bank list
		$this->loadModel('ItBank');
		$fields='ItBank.b_idx, ItBank.b_name, ItBank.b_acc_no, ItBank.b_acc_name, ItBank.b_bsb';
		$this->set('banks', $this->ItBank->find('all', array('fields'=>$fields, 'order'=>'ItBank.b_idx DESC')));
	}
	
	public function Managers_AddNew(){
		$this->set('current_page', 'bank');
		if($this->request->data){
			$this->loadModel('ItBank');
			if($this->ItBank->save($this->request->data)){
				$this->Session->setFlash(__('Bank has been saved.'));
				return $this->redirect(array('action' => 'Managers_index'));
			}
		}
	}
	
	public function Managers_Modify($b_idx=null){
		$this->set('current_page', 'bank');
		
		$this->loadModel('ItBank');
		$post = $this->ItBank->findByBIdx($b_idx);
		
		if($this->request->data){
			if($this->ItBank->save($this->request->data)){
				$this->Session->setFlash(__('Bank has been updated.'));
				return $this->redirect(array('action' => 'Managers_index'));
			}
		}else{
			$this->request->data = $post;
		}
	}
	
	public function Managers_Delete($b_idx=null){
		$this->set('current_page', 'bank');
		
		$this->loadModel('ItBank');
		$this->ItBank->delete($b_idx);
		
		$this->Session->setFlash(__('Bank has been deleted.'));
		return $this->redirect(array('action' => 'Managers_index'));
	}
}
?>