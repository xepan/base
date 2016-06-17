<?php
namespace xepan\base;
class page_forgotpassword extends \Page{
	public $title="Forgot Password";
	function init(){
		parent::init();

		$form=$this->add('Form');
		$form->setLayout('view/admin/user/form/forgotpassword');
		$form->addField('line','email')->validate('required');/*->validateNotNull()->validateField('filter_var($this->get(), FILTER_VALIDATE_EMAIL)');*/

		if($_GET['email_send'])
			$form->layout->add('View',null,'success_message')->addClass('label label-primary col-sm-12 col-md-12')->setStyle('padding-bottom:10px;padding-top:10px;border-radius:0px;')->set('Please check your email');

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
				$subject_temp=$this->add('GiTemplate');
				$subject_temp->loadTemplateFromString($email_subject);
				$temp=$this->add('GiTemplate');
				$temp->loadTemplateFromString($email_body);
				$url=$this->api->url('xepan_base_resetpassword',
												[
												'secret_code'=>$user['hash'],
												'activate_email'=>$form['email'],
												// 'layout'=>'reset_form'
												]
												)->useAbsoluteURL();

				$tag_url="<a href=\"".$url."\">Click here </a>"	;
			
				
				$subject_v=$this->add('View',null,null,$subject_temp);
				$subject_v->setModel($user);
				
				$body_v=$this->add('View',null,null,$temp);
				$body_v->setModel($user);

				$body_v->template->trySetHTML('click_here',$tag_url);

				$mail->setfrom($email_settings['from_email'],$email_settings['from_name']);
				$mail->addTo($form['email']);
				$mail->setSubject($subject_v->getHtml());
				$mail->setBody($body_v->getHtml());
				$mail->send($email_settings);

				return $form->js(null)->reload(array('email_send'=>1))->execute();				
			}
		}

	}
}