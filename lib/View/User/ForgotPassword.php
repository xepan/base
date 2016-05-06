<?php
namespace xepan\base;
class View_User_ForgotPassword extends \View{
	
	function init(){
		parent::init();
		$form=$this->add('Form');
		$form->setLayout($this->options['forgot_form_layout']);
		$form->addField('line','email');

		if($form->isSubmitted()){
			$user=$this->add('xepan\base\Model_User');
			$user->addCondition('username',$form['email']);
			$user->tryLoadAny();
			
			if(!$user->loaded()){
				throw new \Exception($user->id, 1);
				// $form->displayError('email','Email Id Not Register');
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