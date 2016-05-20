<?php
namespace xepan\base;
class View_User_Registration extends \View{
	public $options = [];

	function init(){
		parent::init();
			$f=$this->add('Form',null,null,['form/empty']);
			$f->setLayout('view/tool/userpanel/form/registration');
			$f->addField('line','first_name');
			$f->addField('line','last_name');
			$f->addField('line','email_id')->validate('required');
			$f->addField('password','password')->validate('required');
			$f->addField('password','retype_password');

			$f->onSubmit(function($f){
				if($f['password']!= $f['retype_password']){
					$f->displayError($f->getElement('retype_password'),'Password Not Match');
				}				
				// // throw new \Exception($this->app->auth->model->ref('epan_id')->id, 1);
				
				// $user=$this->app->auth->model;
				$user=$this->add('xepan\base\Model_User');
				
				$this->add('BasicAuth')
				->usePasswordEncryption('md5')
				->addEncryptionHook($user);

				$user['epan_id']=$this->app->epan->id;
				$user['username']=$f['email_id'];
				$user['password']=$f['password'];
				$user['status'] = 'InActive';
				$user['hash']=rand(9999,100000);
				$user->save();

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

				$user->createNewCustomer($f['first_name'],$f['last_name'],$user->id);
			
			return $f->js(null,$f->js()->reload())->univ()->successMessage('Registration SuccessFully');
			});
	}
}