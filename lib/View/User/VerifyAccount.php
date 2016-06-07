<?php
namespace xepan\base;
class View_User_VerifyAccount extends \View{
	public $options = [];
	function init(){
		parent::init();

		$secret_code=$this->app->stickyGET('secret_code');
		$activate_email=$this->app->stickyGET('activate_email');
		
		$form=$this->add('Form',null,null,['form/empty']);
		$form->setLayout('view/tool/userpanel/form/xepanverify');
		$form->addField('line','email')->set($activate_email);	
		$form->addField('line','activation_code')->set($secret_code);

		$form->onSubmit(function($f){
			$user=$this->add('xepan\base\Model_User');	
			$user->addCondition('username',$f['email']);
			$user->tryLoadAny();
			if(!$user->loaded())
				$f->displayError('email','This E-mail Id is not registered');
			
			if($f['activation_code']!=$user['hash'])
				$f->displayError('activation_code','Activation code did not match');

			$email_settings = $this->add('xepan\communication\Model_Communication_EmailSetting')->tryLoadAny();
			$mail = $this->add('xepan\communication\Model_Communication_Email');

			$reg_model=$this->app->epan->config;
			$email_subject=$reg_model->getConfig('VERIFICATIONE_MAIL_SUBJECT');
			$email_body=$reg_model->getConfig('VERIFICATIONE_MAIL_BODY');
			// $email_body=str_replace("{{name}}",$employee['name'],$email_body);
			$temp=$this->add('GiTemplate');
			$temp->loadTemplateFromString($email_body);
			$mail->setfrom($email_settings['from_email'],$email_settings['from_name']);
			$mail->addTo($f['email']);
			$mail->setSubject($email_subject);
			$mail->setBody($temp->render());
			$mail->send($email_settings);
			
			$user['status']='Active';
			$user->save();
			return $f->js(null,$f->js()->reload())->univ()->successMessage('Account verified successfully');
		});
	}			
}			