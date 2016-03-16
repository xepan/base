<?php
namespace xepan\base;
class page_forgotpassword extends \Page{
	public $title="Reset Password";
	function init(){
		parent::init();
		$employee=$this->add('xepan\hr\Model_Employee');
		$employee->load($this->app->employee->id);

		$this->add('H1')->set($employee['name']);
		$email=$employee->ref('Emails')->tryLoadAny();
		
		$form=$this->add('Form');
		$form->setLayout('layout/xepanforgotpassword');
		$form->addField('line','email')->set($email['value'])/*->validateNotNull()->validateField('filter_var($this->get(), FILTER_VALIDATE_EMAIL)')*/;

		$form->onSubmit(function($f){
			$email_settings = $this->add('xepan\base\Model_Epan_EmailSetting')->tryLoadAny();
			$reset_pass=$this->add('xepan\base\Model_Mail_ResetPassword');
			$reset_pass->tryLoadAny();
			$mail = $this->add('xepan\communication\Model_Communication_Email');
			$mail->setfrom($email_settings['from_email'],$email_settings['from_name']);
			$mail->addTo($f['email']);
			$mail->setSubject($reset_pass['subject']);
			$mail->setBody($reset_pass['body']);
			$mail->send($email_settings);
			return $f->js()->univ()->successMessage('OK');
		});

	}
}