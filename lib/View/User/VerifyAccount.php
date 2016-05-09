<?php
namespace xepan\base;
class View_User_VerifyAccount extends \View{
	public $options = [];
	function init(){
		parent::init();

		$secret_code=$this->app->stickyGET('secret_code');
		$activate_email=$this->app->stickyGET('activate_email');
		
		$form=$this->add('Form',null,null,['form/empty']);
		$form->setLayout('view/xepanverify');
		$form->addField('line','email')->set($activate_email);	
		$form->addField('line','activation_code')->set($secret_code);

		$form->onSubmit(function($f){

			$user=$this->add('xepan\base\Model_User');	
			$user->addCondition('username',$f['email']);
			$user->tryLoadAny();
			if(!$user->loaded())
				$f->displayError('email','Email Id Not Register');
			
			if($f['activation_code']!=$user['hash'])
				$f->displayError('activation_code','Activation Code Not Match');

			$verfiy_email=$this->add('xepan\base\Model_Mail_Verification');
			$verfiy_email->verificationMail($f['email']);
			
			$user['status']='Active';
			$user->save();
			return $f->js(null,$f->js()->reload())->univ()->successMessage('Account Verification SuccessFully');
		});
	}			
}			