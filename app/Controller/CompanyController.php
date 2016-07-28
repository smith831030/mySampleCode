<?php
/**
 * ManagersController
 *
 * @author		Smith.Seo(smith831030@gmail.com)
 * @created		16/05/2015
 *
 */
class CompanyController extends AppController {
	public $helpers = array('Html', 'Form', 'Session', 'Text');
	
	public function beforeFilter() {
		$this->layout = 'managers';
		$this->set('mainPageUrl', '/invoice');
		$this->set('current_page', 'company');
		$this->AdminOnly();
	}
	
	public function Managers_index(){
		//Company list
		$this->loadModel('IvInformation');
		$fields='IvInformation.if_idx, IvInformation.if_company, IvInformation.if_logo, IvInformation.if_abn, IvInformation.if_address, IvInformation.if_contact, IvInformation.if_email';
		$this->set('companies', $this->IvInformation->find('all', array('fields'=>$fields, 'order'=>'IvInformation.if_idx DESC')));
	}
	
	public function Managers_AddNewCompany(){
		if($this->request->data){
			if(!empty($this->request->data['IvInformation']['if_logo'])){
				$directory = WWW_ROOT . DS . 'upload' . DS ; 
				
				$arrFilename = explode('.', basename($this->request->data['IvInformation']['if_logo']['name']));
				$new_filename = $arrFilename[0].'_'.time().'.'.$arrFilename[1];
				
				move_uploaded_file($this->data['IvInformation']['if_logo']['tmp_name'], $directory.$new_filename);
				$this->request->data['IvInformation']['if_logo'] = $new_filename; 
			}
			
			$this->loadModel('IvInformation');
			$this->IvInformation->save($this->request->data);
		
			$this->flash('Thanks', 'Managers_index');
			return $this->redirect(array('action' => 'Managers_index'));
		}
	}
	
	public function Managers_Modify($if_idx=null){
		if($this->request->data){
			if(!empty($this->request->data['IvInformation']['if_logo_tmp']['size']) && $this->request->data['IvInformation']['if_logo_tmp']['size']>0){
				$directory = WWW_ROOT . DS . 'upload' . DS ; 
				
				$arrFilename = explode('.', basename($this->request->data['IvInformation']['if_logo_tmp']['name']));
				$new_filename = $arrFilename[0].'_'.time().'.'.$arrFilename[1];
				
				move_uploaded_file($this->data['IvInformation']['if_logo_tmp']['tmp_name'], $directory.$new_filename);
				$this->request->data['IvInformation']['if_logo'] = $new_filename; 
			}
			
			$this->loadModel('IvInformation');
			$this->IvInformation->save($this->request->data);
		
			$this->flash('Thanks', 'Managers_index');
			return $this->redirect(array('action' => 'Managers_index'));
		}else{
			$this->loadModel('IvInformation');
			$information=$this->IvInformation->findByIfIdx($if_idx);
			$this->request->data=$information;
		}
	}
	
	public function Managers_Delete($if_idx){
		$this->loadModel('IvInformation');
		
		$this->IvInformation->delete($if_idx);
		
		$this->Session->setFlash(__('Company information has been deleted.'));
		return $this->redirect(array('action' => 'Managers_index'));
	}
	
}
?>