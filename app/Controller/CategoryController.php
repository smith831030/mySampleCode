<?php
/**
 * MainPageController
 *
 * @author		Smith.Seo(smith831030@gmail.com)
 * @created		16/05/2015
 *
 */
class CategoryController extends AppController {
	public $helpers = array('Html', 'Form', 'Session', 'Text');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->set('mainPageUrl', '/invoice');
		$this->set('current_page', 'category');
		$this->AdminOnly();
		$this->layout = 'managers';
	}
	
	public function Managers_index(){
		$this->loadModel('IvCategory');
		$fields='IvCategory.cate_idx, IvCategory.cate_title, IvCategory.created';
		$this->set('categories', $this->IvCategory->find('all', array('fields'=>$fields, 'order'=>'IvCategory.cate_idx DESC')));
	}
	
	public function Managers_AddNew(){
		if($this->request->data){
			$this->loadModel('IvCategory');
			if($this->IvCategory->save($this->request->data)){
				$this->Session->setFlash(__('Category has been saved.'));
				return $this->redirect(array('action' => 'Managers_index'));
			}
		}
	}
	
	public function Managers_Modify($cate_idx=null){
		$this->loadModel('IvCategory');
		$post = $this->IvCategory->findByCateIdx($cate_idx);
		
		if($this->request->data){
			if($this->IvCategory->save($this->request->data)){
				$this->Session->setFlash(__('Category has been updated.'));
				return $this->redirect(array('action' => 'Managers_index'));
			}
		}else{
			$this->request->data = $post;
		}
	}
	
	public function Managers_Delete($cate_idx=null){
		$this->loadModel('IvCategory');
		$this->IvCategory->delete($cate_idx);
		
		$this->Session->setFlash(__('Category has been deleted.'));
		return $this->redirect(array('action' => 'Managers_index'));
	}
}
?>