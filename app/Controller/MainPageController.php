<?php
/**
 * MainPageController
 *
 * @author		Smith.Seo(smith831030@gmail.com)
 * @created		16/05/2015
 *
 */
class MainPageController extends AppController {
	public $helpers = array('Html', 'Form', 'Session', 'Text');
	
	public function beforeFilter() {
		parent::beforeFilter();
		$this->set('mainPageUrl', '/invoice');
	}
	
	public function index() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->level_check_redirection();
			} else {
				$this->layout = 'signin';
				
				$this->set('login',0);
				$this->Session->setFlash(
				__('Username or password is incorrect'),
				'default',
				array(),
				'auth'
				);
			}
		}elseif(!$this->Auth->loggedIn()){
			$this->layout = 'signin';
			$this->set('login',0);
		}else{
			$this->level_check_redirection();
		}
	}
	
	private function level_check_redirection(){
		if($this->Auth->user('mem_level')>=20){
			$this->Auth->logout();
			//$this->redirect("/");
		}elseif($this->Auth->user('mem_level')>=10){
			return $this->redirect('/Managers/MainPage/index');
		}
	}
	
	public function Logout() {
		$this->Auth->logout();
		$this->redirect("/");
	}
	
	public function CheckId($mem_id){
		$this->layout = 'ajax';
		
		$this->loadModel('Member');
		$conditions=array('Member.mem_id'=>$mem_id);
		$this->set('check', $this->Member->find('count', array('conditions' => $conditions, 'recursive' => -1)));
	}
	
	public function Managers_index() {
		$this->AdminOnly();
		$this->layout = 'managers';
		$this->set('current_page', 'main');
	}
}
?>