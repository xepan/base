<?php
namespace xepan\base;

class Model_Mail_ResetPassword extends \xepan\base\Model_Epan_Configuration{
	function init(){
		parent::init();

		$this->addCondition('application','base');
	}

	function sendResetPasswordMail($email=null){
		// $employee=$this->add('xepan\hr\Model_Employee')->load($this->app->employee->id);
		// $employee_email=$employee->ref('Emails')->setLimit(1)->fieldQuery('value');
		$user=$this->add('xepan\base\Model_User');
		$user->addCondition('username',$email);
		$user->tryLoadAny();
		if(!$user->loaded()) throw new \Exception("User Must Loaded", 1);
		
		$username=$user['username'];
		if($email != $username){
			throw new \Exception("This Email Id  not Ragister", 1);
		}

		$email_settings = $this->add('xepan\communication\Model_Communication_EmailSetting')->tryLoadAny();
		$mail = $this->add('xepan\communication\Model_Communication_Email');

		$reset_pass=$this->add('xepan\base\Model_Mail_ResetPassword');
		$email_subject=$reset_pass->getConfig('RESET_PASSWORD_SUBJECT');
		$email_body=$reset_pass->getConfig('RESET_PASSWORD_BODY');

		// $email_body=str_replace("{{name}}",$employee['name'],$email_body);
		$temp=$this->add('GiTemplate');
		$temp->loadTemplateFromString($email_body);
		$url=$this->api->url('xepan_base_resetpassword',
										[
										'secret_code'=>$user['hash'],
										'activate_email'=>$email
										]
										)->useAbsoluteURL();

		$tag_url="<a href=\"".$url."\">Click Here to Activate </a>"	;
	
		$temp->setHTML('name',$user['name']);
		$temp->setHTML('click_here_to_activate',$tag_url);
		// echo $temp->render();
		// exit;		
		$mail->setfrom($email_settings['from_email'],$email_settings['from_name']);
		$mail->addTo($email);
		$mail->setSubject($email_subject);
		$mail->setBody($temp->render());
		$mail->send($email_settings);
	}
}