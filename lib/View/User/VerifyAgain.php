<?php
namespace xepan\base;
class View_User_VerifyAgain extends \View{
	public $options = [];
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,['form/empty']);
		$form->setLayout('view/xepanverifyagain');
		$form->addField('line','email');

		$form->onSubmit(function($f){
			try {
				$reg_m=$this->add('xepan\base\Model_Mail_Registration');
				$reg_m->sendWelcomeMail($f['email']);

				return $f->js(null,$f->js()->reload())->univ()->successMessage('Secret Code Send');
			} catch (Exception $e) {
				return $this->js()->univ()->errorMessage('Error');	
			}
		});
	}			
}			