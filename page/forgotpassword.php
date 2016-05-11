<?php
namespace xepan\base;
class page_forgotpassword extends \Page{
	public $title="Forgot Password";
	function init(){
		parent::init();

		$form=$this->add('Form');
		$form->setLayout('view/admin/user/form/forgotpassword');
		$form->addField('line','email')->validate('required');/*->validateNotNull()->validateField('filter_var($this->get(), FILTER_VALIDATE_EMAIL)');*/

		if($form->isSubmitted()){
			$user=$this->add('xepan\base\Model_User');
			$user->addCondition('username',$form['email']);
			$user->tryLoadAny();
			if(!$user->loaded()){
				$form->displayError('email','Email Id Not Register');
			}else{
				$user['hash']=rand(9999,100000);
				$user->update();
				
				$email_settings = $this->add('xepan\communication\Model_Communication_EmailSetting')->tryLoadAny();
				$mail = $this->add('xepan\communication\Model_Communication_Email');
				$reset_pass = $this->app->epan->config;
				$email_subject=$reset_pass->getConfig('RESET_PASSWORD_SUBJECT_FOR_ADMIN');
				$email_body=$reset_pass->getConfig('RESET_PASSWORD_BODY_FOR_ADMIN');
				// $email_body=str_replace("{{name}}",$employee['name'],$email_body);
				$temp=$this->add('GiTemplate');
				$temp->loadTemplateFromString($email_body);
				$url=$this->api->url('xepan_base_resetpassword',
												[
												'secret_code'=>$user['hash'],
												'activate_email'=>$form['email'],
												// 'layout'=>'reset_form'
												]
												)->useAbsoluteURL();

				$tag_url="<a href=\"".$url."\">Click Here to Activate </a>"	;
			
				$temp->trySetHTML('name',$user['name']);		
				$temp->trySetHTML('click_here_to_activate',$tag_url);
				$mail->setfrom($email_settings['from_email'],$email_settings['from_name']);
				$mail->addTo($form['email']);
				$mail->setSubject($email_subject);
				$mail->setBody($temp->render());
				$mail->send($email_settings);

				return $form->js(null,$form->js()->univ()->successMessage(' E-Mail SuccessFully Send'))->reload->execute();
			}
		}

	}
}