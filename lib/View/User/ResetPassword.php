<?php
namespace xepan\base;
class View_User_ResetPassword extends \View{
	public $options = [];
	function init(){
		parent::init();

		$secret_code=$this->app->stickyGET('secret_code');
		$activate_email=$this->app->stickyGET('activate_email');

		$form = $this->add('Form');
		$form->setLayout('view/tool/userpanel/form/xepanrestpassword');

		$form->addField('line','email','username')->set($_GET['activate_email'])->validateNotNull();

		$form->addField('line','secret_code','Activation Code')->set($_GET['secret_code'])->validateNotNull();
		$form->addField('password','password')->validateNotNull();
		$form->addField('password','retype_password')->validateNotNull();

		if(!$this->options['show_forgotpassword_link']){
			$form->layout->template->del('forgot_wrapper');        	
        }

        if(!$this->options['show_registration_link']){
			$form->layout->template->del('register_wrapper');        	
        }

        if(!$this->options['show_activation_link']){
			$form->layout->template->del('activate_wrapper');        	
        }

		$form->onSubmit(function($f){

			$user=$this->app->auth->model;	
			$user->addCondition('username',$f['email']);
			$user->tryLoadAny();
			
			if($f['secret_code']!=$user['hash'])
				$f->displayError('secret_code','Activation Code Not Match');
			
			if($f['password']=='')
				$f->displayError($f->getElement('password'),'Password Required Field');
			
			if($f['password']!= $f['retype_password'])
				$f->displayError($f->getElement('retype_password'),'Password did not match');

			$contact=$user->ref('Contacts')->tryLoadAny();
			
			$frontend_config_m = $this->add('xepan\base\Model_ConfigJsonModel',
			[
				'fields'=>[
							'user_registration_type'=>'DropDown',
							'reset_subject'=>'Line',
							'reset_body'=>'xepan\base\RichText',
							'reset_sms_content'=>'Text',
							'update_subject'=>'Line',
							'update_body'=>'xepan\base\RichText',
							'update_sms_content'=>'Text',
							'registration_subject'=>'Line',
							'registration_body'=>'xepan\base\RichText',
							'registration_sms_content'=>'Text',
							'verification_subject'=>'Line',
							'verification_body'=>'xepan\base\RichText',
							'verification_sms_content'=>'Text',
							'subscription_subject'=>'Line',
							'subscription_body'=>'xepan\base\RichText',
						],
					'config_key'=>'FRONTEND_LOGIN_RELATED_EMAIL',
					'application'=>'communication'
			]);
			$frontend_config_m->tryLoadAny();

			$merge_model_array=[];
			$merge_model_array = array_merge($merge_model_array,$user->get());
			$merge_model_array = array_merge($merge_model_array,$contact->get());
			
			if($this->options['registration_mode'] === "email"){

				$email_settings = $this->add('xepan\communication\Model_Communication_DefaultEmailSetting')->tryLoadAny();
				$mail = $this->add('xepan\communication\Model_Communication_Email');

				$email_subject = $frontend_config_m['update_subject'];
				$email_body = $frontend_config_m['update_body'];
				
				$temp=$this->add('GiTemplate');
				$temp->loadTemplateFromString($email_body);

				$subject_temp=$this->add('GiTemplate');
				$subject_temp->loadTemplateFromString($email_subject);
				$subject_v=$this->add('View',null,null,$subject_temp);
				$subject_v->template->trySet($merge_model_array);

				$temp=$this->add('GiTemplate');
				$temp->loadTemplateFromString($email_body);
				$body_v=$this->add('View',null,null,$temp);
				$body_v->template->trySet($merge_model_array);					

				$mail->setfrom($email_settings['from_email'],$email_settings['from_name']);
				$mail->addTo($f['email']);
				$mail->setSubject($subject_v->getHtml());
				$mail->setBody($body_v->getHtml());
				$mail->send($email_settings);
			}

			if($this->options['registration_mode'] === "sms"){
				if($message = $frontend_config_m['update_sms_content']){
					$temp = $this->add('GiTemplate');
					$temp->loadTemplateFromString($message);
					$msg = $this->add('View',null,null,$temp);
					$msg->template->trySet($merge_model_array);
					$this->add('xepan\communication\Controller_Sms')->sendMessage($f['username'],$msg->getHtml());
				}
			}

			$user['password']=$f['password'];
			$user->save();

			return $f->js(null,$f->js()->redirect($this->app->url('login',['layout'=>'login_view','message'=>"Password  SuccessFully Changed"])))->univ()->successMessage('Password SuccessFully Changed');
			// return $f->js()->univ()->successMessage('Password  SuccessFully Changed');
		});
	}
}