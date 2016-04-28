<?php
namespace xepan\base;
class View_User_Registration extends \View{
	function init(){
		parent::init();
		$verifyAccount=$this->app->stickyGET('verifyAccount');
		$secret_code=$this->app->stickyGET('secret_code');
		$activate_email=$this->app->stickyGET('activate_email');
		if(!$verifyAccount){
			$f=$this->add('Form',null,null,['form/empty']);
			$f->setLayout(['view/registration']);
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
				// $user['status']='Active';
				$user['hash']=rand(9999,100000);

				$reg_m=$this->add('xepan\base\Model_Mail_Registration');
				$reg_m->sendWelcomeMail($f['email_id']);

				$this->api->auth->addEncryptionHook($user);

				$user->save();
				// $user->createNewCustomer($f['first_name'],$f['last_name'],$f['email_id']);
			return $f->js(null,$f->js()->reload())->univ()->successMessage('Registration SuccessFully');
			});
		}else{
			$form=$this->add('Form',null,null,['form/empty']);
			$form->setLayout(['view/xepanverify']);
			$form->addField('line','email')->set($activate_email);
			$form->addField('line','activation_code')->set($secret_code);

			$form->onSubmit(function($f)use($activate_email){
				$user=$this->add('xepan\base\Model_User');	
				$user->addCondition('username',$activate_email);
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
}