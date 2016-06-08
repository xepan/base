<?php
namespace xepan\base;
class View_User_VerifyAgain extends \View{
	public $options = [];
	function init(){
		parent::init();

		$form=$this->add('Form',null,null,['form/empty']);
		$form->setLayout('view/tool/userpanel/form/xepanverifyagain');
		$form->addField('line','email');

		$form->onSubmit(function($f){
			try {
				$user=$this->add('xepan\base\Model_User');
				$user->addCondition('username',$f['email']);
				$user->tryLoadAny();
				
				if(!$user->loaded()) throw $this->exception('Email Id is not registered','ValidityCheck')->setField('email');

				$contact=$user->ref('Contacts');
				$email_settings = $this->add('xepan\communication\Model_Communication_EmailSetting')->tryLoadAny();
				$mail = $this->add('xepan\communication\Model_Communication_Email');

				$reg_model=$this->app->epan->config;
				$email_subject=$reg_model->getConfig('REGISTRATION_SUBJECT');
				$email_body=$reg_model->getConfig('REGISTRATION_BODY');

				// $email_body=str_replace("{{name}}",$employee['name'],$email_body);
				$temp=$this->add('GiTemplate');
				$temp->loadTemplateFromString($email_body);
				$url=$this->api->url(null,
											[
											'secret_code'=>$user['hash'],
											'activate_email'=>$f['email'],
											'layout'=>'verify_account',
											]
									)->useAbsoluteURL();

				$tag_url="<a href=\"".$url."\">Click Here to Activate </a>"	;
						
				$temp->trySetHTML('name',$contact['name']);
				$temp->trySetHTML('otp',$user['hash']);
				$temp->trySetHTML('password',$user['password']);
				$temp->trySetHTML('email_id',$user['username']);
				$temp->trySetHTML('click_here_to_activate',$tag_url);
				// echo $temp->render();
				// exit;		
				$mail->setfrom($email_settings['from_email'],$email_settings['from_name']);
				$mail->addTo($f['email']);
				$mail->setSubject($email_subject);
				$mail->setBody($temp->render());
				$mail->send($email_settings);

				return $f->js(null,$f->js()->redirect($this->app->url('login',['layout'=>'login_view'])))->univ()->successMessage('Secret Code Sent');
			} catch (Exception $e) {
				return $this->js()->univ()->errorMessage('Error');	

			}
		});
	}			
}			