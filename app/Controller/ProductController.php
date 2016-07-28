<?php
/**
 * MainPageController
 *
 * @author		Smith.Seo(smith831030@gmail.com)
 * @created		16/05/2015
 *
 */
class ProductController extends AppController {
	public $helpers = array('Html', 'Form', 'Session', 'Text');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->set('mainPageUrl', '/invoice');
		$this->set('current_page', 'product');
		$this->AdminOnly();
		$this->layout = 'managers';
	}
	
	public function Managers_index(){
		$this->loadModel('IvProduct');
		$fields='IvProduct.pro_idx, IvProduct.pro_name, IvProduct.pro_price, IvProduct.pro_type, IvProduct.created, IvCategory.cate_title';
		$joins=array(array(
					'table'=>'os_iv_categories',
					'alias'=>'IvCategory',
					'type'=>'INNER',
					'conditions'=>array('IvProduct.cate_idx=IvCategory.cate_idx')
				)
			);
		$this->set('products', $this->IvProduct->find('all', array('fields'=>$fields, 'joins'=>$joins, 'order'=>'IvProduct.pro_idx DESC')));
	}
	
	public function Managers_AddNew(){
		if($this->request->data){
			$this->loadModel('IvProduct');
			if($this->IvProduct->save($this->request->data)){
				$this->Session->setFlash(__('Product has been saved.'));
				return $this->redirect(array('action' => 'Managers_index'));
			}
		}else{
			$this->loadModel('IvCategory');
			$fields='IvCategory.cate_idx, IvCategory.cate_title';
			$categories=$this->IvCategory->find('list', array('fields'=>$fields));
			$this->set(compact('categories'));
		}
	}
	
	public function Managers_Modify($pro_idx=null){
		$this->loadModel('IvProduct');
		$post = $this->IvProduct->findByProIdx($pro_idx);
		
		if($this->request->data){
			if($this->IvProduct->save($this->request->data)){
				$this->Session->setFlash(__('Product has been updated.'));
				return $this->redirect(array('action' => 'Managers_index'));
			}
		}else{
			$this->request->data = $post;
			$this->loadModel('IvCategory');
			$fields='IvCategory.cate_idx, IvCategory.cate_title';
			$categories=$this->IvCategory->find('list', array('fields'=>$fields));
			$this->set(compact('categories'));
		}
	}
	
	public function Managers_Delete($pro_idx=null){
		$this->loadModel('IvProduct');
		$this->IvProduct->delete($pro_idx);
		
		$this->Session->setFlash(__('Product has been deleted.'));
		return $this->redirect(array('action' => 'Managers_index'));
	}
}
?>