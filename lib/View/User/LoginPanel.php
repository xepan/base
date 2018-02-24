<?php
namespace xepan\base;

class View_User_LoginPanel extends \View{
	public $options = [];
	public $reload_object;

	function init(){
		parent::init();

        $f = $this->add('Form',null,null,['form/minimal']);
        $f->setLayout('view/tool/userpanel/form/login');
        if($message = $this->app->stickyGET('message')){
   	 		$f->layout->template->trySet('message',$message);
        }else{
        	$f->layout->template->tryDel('message_wrapper');
        }

        if(!$this->options['show_footer']){
			$f->layout->template->del('footer_wrapper');        	
        }

        if(!$this->options['show_forgotpassword_link']){
			$f->layout->template->del('forgot_wrapper');        	
        }

        if(!$this->options['show_registration_link']){
			$f->layout->template->del('register_wrapper');        	
        }

        if(!$this->options['show_activation_link']){
			$f->layout->template->del('activate_wrapper');        	
        }


        $f->addField('Line','username');
        $f->addField('Password','password','Password');
 		
        if($f->isSubmitted()){
        	
			$auth = $this->add('BasicAuth');
			$auth->setModel('xepan\base\Model_User','username','password');
			$auth->usePasswordEncryption('md5');

			if(!$credential = $auth->verifyCredentials($f['username'],$f['password'])){
				$f->displayError($f->getElement('password'),'Wrong credentials');
			}
					
			$user = $this->add('xepan\base\Model_User')->load($credential);
			
			if($user['status']=='InActive')
				$f->displayError('username','Please Activate Your Account First, check email (Including Spam/Junk folders)');
			
			$this->app->auth->login($f['username']);
			$this->app->hook('login_panel_user_loggedin',[$auth->model]);

			if($next_url = $this->app->recall('next_url'))
				$this->app->redirect($this->api->url($next_url))->execute();

			if($this->reload_object){
				$object = $this->reload_object;
				$this->js(null,$object->js()->reload())->univ()->successMessage('wait ... ')->execute();
			}
			else{
				$success_url = $this->options['login_success_url'];
				$this->app->redirect($this->app->url($success_url));
			}
        }
	}
}