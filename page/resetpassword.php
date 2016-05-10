<?php
namespace xepan\base;
class page_resetpassword extends \Page{
	public $title="Reset Password";
	function init(){
		parent::init();
		$secret_code=$this->app->stickyGET('secret_code');
		$activate_email=$this->app->stickyGET('activate_email');
		$user=$this->app->auth->model;	
		$user->addCondition('username',$activate_email);
		$user->tryLoadAny();
		
		$form=$this->add('Form');
		$form->setLayout('view//admin/user/form/restpassword');
		$form->addField('line','email')->set($_GET['activate_email'])->validate('required');
		$form->addField('line','secret_code','Activation Code')->set($_GET['secret_code'])->validate('required');

		$form->addField('password','password')->validate('required');
		$form->addField('password','retype_password')->validateNotNull();

		$form->onSubmit(function($f)use($user){
			if($f['secret_code']!=$user['hash']){
				$f->displayError('secret_code','Activation Code Not Match');
			}
			if($f['password']!= $f['retype_password']){
				$f->displayError($f->getElement('retype_password'),'Password Not Match');
			}
			$contact=$user->ref('Contacts');
			$email_settings = $this->add('xepan\communication\Model_Communication_EmailSetting')->tryLoadAny();
			$mail = $this->add('xepan\communication\Model_Communication_Email');

			$reg_model=$this->app->epan->config;
			$email_subject=$reg_model->getConfig('UPDATE_PASSWORD_SUBJECT_FOR_ADMIN');
			$email_body=$reg_model->getConfig('UPDATE_PASSWORD_BODY_FOR_ADMIN');
			// $email_body=str_replace("{{name}}",$employee['name'],$email_body);
			$temp=$this->add('GiTemplate');
			$temp->loadTemplateFromString($email_body);
			$mail->setfrom($email_settings['from_email'],$email_settings['from_name']);
			$mail->addTo($f['email']);
			$mail->setSubject($email_subject);
			$mail->setBody($temp->render());
			$mail->send($email_settings);
			
			$user['password']=$f['password'];
			$user->save();
			
			// $this->app->auth->model['password']=$f['new_password'];
			// $this->app->auth->model->save();

			
			return $f->js()->univ()->successMessage('Password  SuccessFully Change');
		});

	}
}