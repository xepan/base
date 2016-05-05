<?php
namespace xepan\base;

class Model_Mail_Registration extends \xepan\base\Model_Epan_Configuration{
	function init(){
		parent::init();

		$this->addCondition('application','base');
	}

	function sendWelcomeMail($email){
		$user=$this->add('xepan\base\Model_User');
		$user->addCondition('username',$email);
		$user->tryLoadAny();
		
		if(!$user->loaded()) throw new \Exception("User Must Loaded", 1);
		
		$username=$user['username'];
		if($email != $username){
			throw new \Exception("This Email Id  not Ragister", 1);
		}

		$contact=$user->ref('Contacts');
		$email_settings = $this->add('xepan\communication\Model_Communication_EmailSetting')->tryLoadAny();
		$mail = $this->add('xepan\communication\Model_Communication_Email');

		$reg_model=$this->add('xepan\base\Model_Mail_Registration');
		$email_subject=$reg_model->getConfig('REGISTRATION_SUBJECT');
		$email_body=$reg_model->getConfig('REGISTRATION_BODY');

		// $email_body=str_replace("{{name}}",$employee['name'],$email_body);
		$temp=$this->add('GiTemplate');
		$temp->loadTemplateFromString($email_body);
		$url=$this->api->url(null,
									[
									'secret_code'=>$user['hash'],
									'activate_email'=>$email,
									'layout'=>'verify_account',
									]
							)->useAbsoluteURL();

		$tag_url="<a href=\"".$url."\">Click Here to Activate </a>"	;
				
		$temp->trySetHTML('name',$contact['name']);
		$temp->trySetHTML('otp',$user['hash']);
		$temp->trySetHTML('password',$user['password']);
		$temp->trySetHTML('email_id',$user['username']);
		$temp->trySetHTML('click_here_to_activate',$tag_url);
		// echo $temp->render();
		// exit;		
		$mail->setfrom($email_settings['from_email'],$email_settings['from_name']);
		$mail->addTo($email);
		$mail->setSubject($email_subject);
		$mail->setBody($temp->render());
		$mail->send($email_settings);
	}
}