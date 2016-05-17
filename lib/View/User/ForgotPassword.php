<?php
namespace xepan\base;
class View_User_ForgotPassword extends \View{
	
	function init(){
		parent::init();
		$form=$this->add('Form');
		$form->setLayout('view/tool/userpanel/form/xepanforgotpassword');
		$form->addField('line','email')->validate('required');

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
				$email_subject=$reset_pass->getConfig('RESET_PASSWORD_SUBJECT');
				$email_body=$reset_pass->getConfig('RESET_PASSWORD_BODY');
				// $email_body=str_replace("{{name}}",$employee['name'],$email_body);
				$temp=$this->add('GiTemplate');
				$temp->loadTemplateFromString($email_body);
				$url=$this->api->url(null,
												[
												'secret_code'=>$user['hash'],
												'activate_email'=>$form['email'],
												'layout'=>'reset_form'
												]
												)->useAbsoluteURL();

				$tag_url="<a href=\"".$url."\">Click Here to Activate </a>"	;
			
				$temp->trySetHTML('name',$user['name']);		
				$temp->trySetHTML('click_here_to_activate',$tag_url);
				// echo $temp->render();
				// exit;		
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