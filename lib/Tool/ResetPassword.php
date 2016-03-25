<?php
namespace xepan\base;
class Tool_ResetPassword extends \xepan\cms\View_Tool{
	function init(){
		parent::init();

		$secret_code=$this->app->stickyGET('secret_code');
		$activate_email=$this->app->stickyGET('activate_email');
		$user=$this->add('xepan\base\Model_User');	
		$user->addCondition('username',$activate_email);
		$user->tryLoadAny();
		
		$form=$this->add('Form');
		$form->setLayout('layout/xepanrestpassword');
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
			$user['password']=$f['password'];
			$user->save();
			$update_pass_model=$this->add('xepan\base\Model_Mail_UpdatePassword');
			$update_pass_model->updatePassword($f['email']);
			
			return $f->js()->univ()->successMessage('Password  SuccessFully Change');
		});
	}
}