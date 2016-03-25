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
		$reset_pass=$this->add('xepan\base\Model_Mail_ResetPassword');
		$reset_pass->addCondition('epan_id',$this->app->auth->model['epan_id']);
		$reset_pass->tryLoadAny();

		$form=$this->add('Form',null,'reset_email');
		$form->setModel($reset_pass);
		$form->addSubmit();

		if($form->isSubmitted()){
			$form->update();
			$form->js(null,$form->js()->reload())->univ()->successMessage('Update Information')->execute();
		}
		/*Registration Email Content*/
		$reg=$this->add('xepan\base\Model_Mail_Registration');
		$reg->addCondition('epan_id',$this->app->auth->model['epan_id']);
		$reg->tryLoadAny();
		$form=$this->add('Form',null,'registration_view');
		$form->setModel($reg);
		$form->addSubmit();

		if($form->isSubmitted()){
			$form->update();
			$form->js(null,$form->js()->reload())->univ()->successMessage('Update Information')->execute();
		}
	}
	
	function defaultTemplate(){
		return ['page/general-setting'];
	}
}