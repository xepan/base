<?php
namespace xepan\base;
class page_forgotpassword extends \Page{
	public $title="Forgot Password";
	function init(){
		parent::init();
		
		$form=$this->add('Form');
		$form->setLayout('layout/xepanforgotpassword');
		$form->addField('line','email');/*->validateNotNull()->validateField('filter_var($this->get(), FILTER_VALIDATE_EMAIL)');*/

		if($form->isSubmitted()){
			$user=$this->add('xepan\base\Model_User');
			$user->addCondition('username',$form['email']);
			$user->tryLoadAny();
			if(!$user->loaded()){
				$form->displayError('email','Email Id Not Register');
			}else{
				$user['hash']=rand(9999,100000);
				$user->update();
				
				$reset_pass=$this->add('xepan\base\Model_Mail_ResetPassword');
				$reset_pass->sendResetPasswordMail($form['email']);

				return $form->js(null,$form->js()->univ()->successMessage(' E-Mail SuccessFully Send'))->reload->execute();
			}
		}

	}
}