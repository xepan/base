<?php
namespace xepan\base;
class Model_Mail_UpdatePassword extends \xepan\base\Model_Epan_Configuration{
	function init(){
		parent::init();

		$this->addCondition('app','base');
	}

	function updatePassword($email){
		$user=$this->add('xepan\base\Model_User');
		$user->addCondition('username',$email);
		$user->tryLoadAny();
		$username=$user['username'];
		if($email != $username){
			throw new \Exception("This Email Id  not Ragister", 1);
		}
		if(!$user->loaded()) throw new \Exception("User Must Loaded", 1);
		$contact=$user->ref('Contacts');
		$email_settings = $this->add('xepan\base\Model_Epan_EmailSetting')->tryLoadAny();
		$mail = $this->add('xepan\communication\Model_Communication_Email');

		$reg_model=$this->add('xepan\base\Model_Mail_UpdatePassword');
		$email_subject=$reg_model->getConfig('UpdatePasswordSubject');
		$email_body=$reg_model->getConfig('UpdatePasswordBody');
		// $email_body=str_replace("{{name}}",$employee['name'],$email_body);
		$temp=$this->add('GiTemplate')->loadTemplateFromString($email_body);
		$mail->setfrom($email_settings['from_email'],$email_settings['from_name']);
		$mail->addTo($email);
		$mail->setSubject($email_subject);
		$mail->setBody($temp->render());
		$mail->send($email_settings);
	}
}	