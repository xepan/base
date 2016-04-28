<?php
namespace xepan\base;
class View_User_ResetPassword extends \View{
	function init(){
		parent::init();

		$secret_code=$this->app->stickyGET('secret_code');
		$activate_email=$this->app->stickyGET('activate_email');
		$user=$this->app->auth->model;	
		$user->addCondition('username',$activate_email);
		$user->tryLoadAny();
		
		$form=$this->add('Form');
		$form->setLayout('view/xepanrestpassword');
		$form->addField('line','email')->set($_GET['activate_email'])->validateNotNull();
		$form->addField('line','secret_code','Activation Code')->set($_GET['secret_code'])->validateNotNull();

		$form->addField('password','password')->validateNotNull();
		$form->addField('password','retype_password')->validateNotNull();

		$form->onSubmit(function($f)use($user){
			if($f['secret_code']!=$user['hash']){
				$f->displayError('secret_code','Activation Code Not Match');
			}
			if($f['password']==''){
				$f->displayError($f->getElement('password'),'Password Required Field');
			}
			if($f['password']!= $f['retype_password']){
				$f->displayError($f->getElement('retype_password'),'Password Not Match');
			}
			$update_pass_model=$this->add('xepan\base\Model_Mail_UpdatePassword');
			$update_pass_model->updatePassword($f['email']);
			
			$user['password']=$f['password'];
			$user->save();
			
			// $this->app->auth->model['password']=$f['new_password'];
			// $this->app->auth->model->save();

			
			return $f->js()->univ()->successMessage('Password  SuccessFully Change');
		});
	}
}