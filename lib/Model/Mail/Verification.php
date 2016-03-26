<?php
namespace xepan\base;
class Model_Mail_Verification extends \xepan\base\Model_Epan_Configuration{
	function init(){
		parent::init();

		$this->addCondition('application','base');
	}

	function verificationMail($email){
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

		$reg_model=$this->add('xepan\base\Model_Mail_Verification');
		$email_subject=$reg_model->getConfig('VerificationEmailSubject');
		$email_body=$reg_model->getConfig('VerificationEmailBody');
		// $email_body=str_replace("{{name}}",$employee['name'],$email_body);
		$temp=$this->add('GiTemplate')->loadTemplateFromString($email_body);
		$mail->setfrom($email_settings['from_email'],$email_settings['from_name']);
		$mail->addTo($email);
		$mail->setSubject($email_subject);
		$mail->setBody($temp->render());
		$mail->send($email_settings);
	}
}	