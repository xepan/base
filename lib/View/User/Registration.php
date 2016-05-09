<?php
namespace xepan\base;
class View_User_Registration extends \View{
	public $options = [];

	function init(){
		parent::init();
			$f=$this->add('Form',null,null,['form/empty']);
			$f->setLayout('view/registration');
			$f->addField('line','first_name');
			$f->addField('line','last_name');
			$f->addField('line','email_id')->validate('required');
			$f->addField('password','password')->validate('required');
			$f->addField('password','retype_password');

			$f->onSubmit(function($f){
				if($f['password']!= $f['retype_password']){
					$f->displayError($f->getElement('retype_password'),'Password Not Match');
				}				
				// // throw new \Exception($this->app->auth->model->ref('epan_id')->id, 1);
				
				// $user=$this->app->auth->model;
				$user=$this->add('xepan\base\Model_User');
				$user['epan_id']=$this->app->auth->model->ref('epan_id')->id;
				$user['username']=$f['email_id'];
				$user['password']=$f['password'];
				$user['hash']=rand(9999,100000);
				$user->save();

				$this->api->auth->addEncryptionHook($user);
				$reg_m=$this->add('xepan\base\Model_Mail_Registration');
				$reg_m->sendWelcomeMail($f['email_id']);

				$user->createNewCustomer($f['first_name'],$f['last_name'],$user->id);
			
			return $f->js(null,$f->js()->reload())->univ()->successMessage('Registration SuccessFully');
			});
	}
}