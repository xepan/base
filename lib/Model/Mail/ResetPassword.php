<?php
namespace xepan\base;

class Model_Mail_ResetPassword extends \xepan\base\Model_Mail_Content{
	function init(){
		parent::init();

		$this->addCondition('type','ResetPassword');
	}

	function sendResetPasswordMail($email=null){
		$employee=$this->add('xepan\hr\Model_Employee')->load($this->app->employee->id);
		$employee_email=$employee->ref('Emails')->setLimit(1)->fieldQuery('value');
		$user=$employee->ref('user_id');
				
		if(!$user->loaded()) throw new \Exception("User Must Loaded", 1);
		
		if($email != $employee_email){
			throw new \Exception("This Email Id  not Ragister", 1);
		}

		$email_settings = $this->add('xepan\base\Model_Epan_EmailSetting')->tryLoadAny();
		$mail = $this->add('xepan\communication\Model_Communication_Email');

		$reset_pass=$this->add('xepan\base\Model_Mail_ResetPassword');
		$reset_pass->tryLoadAny();
		$email_body=$reset_pass['body'];

		// $email_body=str_replace("{{name}}",$employee['name'],$email_body);
		$temp=$this->add('GiTemplate')->loadTemplateFromString($email_body);
		$url=$this->api->url('xepan_base_resetpassword',
										[
										'secret_code'=>$user['hash'],
										'activate_email'=>$email
										]
										)->useAbsoluteURL();

		$tag_url="<a href=\"".$url."\">Click Here to Activate </a>"	;
	
		$temp->setHTML('name',$employee['name']);
		$temp->setHTML('click_here_to_activate',$tag_url);
		// echo $temp->render();
		// exit;		
		$mail->setfrom($email_settings['from_email'],$email_settings['from_name']);
		$mail->addTo($email);
		$mail->setSubject($reset_pass['subject']);
		$mail->setBody($temp->render());
		$mail->send($email_settings);
	}
}