<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class Member extends AppModel {
	public $primaryKey = 'mem_idx';
	
	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['mem_pwd'])) {
			$passwordHasher = new SimplePasswordHasher();
			$this->data[$this->alias]['mem_pwd'] = $passwordHasher->hash(
				$this->data[$this->alias]['mem_pwd']
			);
		}
		return true;
	}
}
?>