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
			$f->addField('line','Username','email_id')->validate('required');
			$f->addField('password','password')->validate('required');
			$f->addField('password','retype_password');

			$f->onSubmit(function($f){
				if($f['password']!= $f['retype_password']){
					$f->displayError($f->getElement('retype_password'),'Password did not match');			
				}
				
				$user=$this->add('xepan\base\Model_User');
				$this->add('BasicAuth')
				->usePasswordEncryption('md5')
				->addEncryptionHook($user);

				$user['epan_id']=$this->app->epan->id;
				$user['username']=$f['Username'];
				$user['password']=$f['password'];

				$frontend_config = $this->app->epan->config;
				$reg_type = $frontend_config->getConfig('REGISTRATION_TYPE');
				if($reg_type =='default_activated'){
					$user['status'] = 'Active';
					$user->save();
				}elseif($reg_type =='admin_activated'){
					$user['status'] = 'InActive';
					$user->save();
				
				}else{

					$user['status'] = 'InActive';
					$user['hash']=rand(9999,100000);
					$user->save();
					$contact=$user->ref('Contacts')->tryLoadAny();
					$email_settings = $this->add('xepan\communication\Model_Communication_EmailSetting')->tryLoadAny();
					$mail = $this->add('xepan\communication\Model_Communication_Email');

					$merge_model_array=[];
					$merge_model_array = array_merge($merge_model_array,$user->get());
					$merge_model_array = array_merge($merge_model_array,$contact->get());

					$reg_model=$this->app->epan->config;
					$email_subject=$reg_model->getConfig('REGISTRATION_SUBJECT');
					$email_body=$reg_model->getConfig('REGISTRATION_BODY');
					// $email_body=str_replace("{{name}}",$employee['name'],$email_body);
					$temp=$this->add('GiTemplate');
					$temp->loadTemplateFromString($email_body);
					$url=$this->api->url(null,
												[
												'secret_code'=>$user['hash'],
												'activate_email'=>$f['email_id'],
												'layout'=>'verify_account',
												]
										)->useAbsoluteURL();

					$tag_url="<a href=\"".$url."\">Click Here </a>"	;

					$subject_temp=$this->add('GiTemplate');
					$subject_temp->loadTemplateFromString($email_subject);
					$subject_v=$this->add('View',null,null,$subject_temp);
					$subject_v->template->trySet($merge_model_array);

					$body_v=$this->add('View',null,null,$temp);
					$body_v->template->trySet($merge_model_array);					
					$t=$body_v->template->trySetHTML('click_here',$tag_url);		
					$t=$body_v->template->trySetHTML('url',$url);		
					$mail->setfrom($email_settings['from_email'],$email_settings['from_name']);
					$mail->addTo($f['email_id']);
					$mail->setSubject($subject_v->getHtml());
					$mail->setBody($body_v->getHtml());
					$mail->send($email_settings);						
				}
				
				$this->app->hook('userCreated',[$f['first_name'],$f['last_name'],$user]);
			
			return $f->js(null,$f->js()->redirect($this->app->url('login',['layout'=>'login_view','message'=>$this->options['registration_message']])))->univ()->successMessage('Account Verification Mail Sent');
			});
	}
}