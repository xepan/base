<?php
namespace xepan\base;

class page_generalsetting extends \Page{
	public $title="General Settings";
	function init(){
		parent::init();

		$setiingview=$this->add('xepan\hr\CRUD',['action_page'=>'xepan_base_general_email'],'general_setting',['view/setting/email-setting-grid']);
		$setiingview->setModel('xepan\base\Epan_EmailSetting');

		// $this->add('xepan\base\View_Emails',null,'email');
		/*Reset Password Email Content*/
		$resetpass_config = $this->app->epan->config;
		$reset_subject = $resetpass_config->getConfig('ResetPasswordSubject');
		$reset_body = $resetpass_config->getConfig('ResetPasswordBody');
		$form=$this->add('Form',null,'reset_email');
		$form->addField('line','subject')->set($reset_subject);
		$form->addField('xepan\base\RichText','subject')->set($reset_body);
		$form->addSubmit('Update');

		if($form->isSubmitted()){
			$resetpass_config->setConfig('ResetPasswordSubject',$form['subject'],'base');

			$registration_config->setConfig('ResetPasswordBody',$form['Body'],'base');
			$form->js(null,$form->js()->reload())->univ()->successMessage('Update Information')->execute();
		}

		/*Registration Email Content*/
		$registration_config = $this->app->epan->config;
		$reg_subject = $registration_config->getConfig('RegistrationSubject','base');
		$reg_body = $registration_config->getConfig('RegistrationBody','base');
		
		$form=$this->add('Form',null,'registration_view');
		$form->addField('line','subject')->set($reg_subject);
		$form->addField('xepan\base\RichText','Body')->set($reg_body);
		$form->addSubmit('Update');

		if($form->isSubmitted()){
			$registration_config->setConfig('RegistrationSubject',$form['subject'],'base');

			$registration_config->setConfig('RegistrationBody',$form['Body'],'base');

			$form->js(null,$form->js()->reload())->univ()->successMessage('Update Information')->execute();
		}
		/*Verification Email Content*/
		$verify_config = $this->app->epan->config;
		$verify_subject = $verify_config->getConfig('VerificationEmailSubject');
		$verify_body = $verify_config->getConfig('VerificationEmailBody');
		$form=$this->add('Form',null,'verification_view');
		$form->addField('line','subject')->set($verify_subject);
		$form->addField('xepan\base\RichText','subject')->set($verify_body);
		$form->addSubmit('Update');

		if($form->isSubmitted()){
			$verify_config->setConfig('VerificationEmailSubject',$form['subject'],'base');

			$verify_config->setConfig('VerificationEmailBody',$form['Body'],'base');
			$form->js(null,$form->js()->reload())->univ()->successMessage('Update Information')->execute();
		}

		/*Update Password Email Content*/
		$update_config = $this->app->epan->config;
		$update_subject = $update_config->getConfig('UpdatePasswordSubject');
		$update_body = $update_config->getConfig('UpdatePasswordBody');
		$form=$this->add('Form',null,'updatepassword_view');
		$form->addField('line','subject')->set($update_subject);
		$form->addField('xepan\base\RichText','subject')->set($update_body);
		$form->addSubmit('Update');

		if($form->isSubmitted()){
			$update_config->setConfig('UpdatePasswordSubject',$form['subject'],'base');

			$update_config->setConfig('UpdatePasswordBody',$form['Body'],'base');
			$form->js(null,$form->js()->reload())->univ()->successMessage('Update Information')->execute();
		}
	}
	
	function defaultTemplate(){
		return ['page/general-setting'];
	}
}