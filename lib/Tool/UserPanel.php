<?php

namespace xepan\base;

class Tool_UserPanel extends \xepan\cms\View_Tool{
	public $options = [
				'layout'=>'login_view',
				'login_success_url'=>'index',
				'login_form_layout'=>'view/login-panel', //html file 
				'forgot_form_layout'=>'view/xepanforgotpassword', //html file 
				'registration_form_layout'=>'view/registration', //html file 
				'reset_form_layout'=>'view/xepanrestpassword', //html file 
				'verify_account_layout'=>'view/xepanverify', //html file 
				'verify_again_layout'=>'view/xepanverifyagain', //html file 
				'already_loggedin_layout'=>'view/alreadyloggedin', //html file 
				'micro_login_layout'=>'view/micrologin', //html file 
				'logout_page'=>'logout',
				'login_page'=>'login',
				'show_micro_login'=>false
			];	
	function init(){
		parent::init();

		if(!in_array($this->options['layout'], ['login_view','forget_password','new_registration','micro_login'])){
			$this->add('View_Error')->set('View ('.$this->options['layout'].') Not Found');
			return;
		}

		$layout = $this->app->stickyGET('layout');
		if($layout){			
			$this->options['layout']=$layout;
		}

		
		$view_url = $this->api->url(null,['cut_object'=>$this->name]);

		$this->on('click','a.xepan-login-panl-loadview',function($js,$data)use($view_url){
			return $this->js()->reload(['layout'=>$data['showview']],null,$view_url);
		});

		if($this->options['show_micro_login']){
			$ml_view=$this->add('xepan\base\View_User_MicroLogin',array('options'=>$this->options));
			$this->app->stickyForget('options');	
			return;
		}

		if(!$this->app->auth->isLoggedIn()){
			
			switch ($this->options['layout']) {
				case 'login_view':
					$user_login=$this->add('xepan\base\View_User_LoginPanel',array('options'=>$this->options));
					$this->app->stickyForget('layout');
				break;

				case 'forget_password':
					$f_view=$this->add('xepan\base\View_User_ForgotPassword',array('options'=>$this->options));
					$this->app->stickyForget('options');
				break;

				case 'new_registration':
					$r_view=$this->add('xepan\base\View_User_Registration',array('options'=>$this->options));
					$this->app->stickyForget('options');
				break;

				case 'verify_account':
					$v_view=$this->add('xepan\base\View_User_VerifyAccount',array('options'=>$this->options));
					$this->app->stickyForget('options');
				break;

				case 'verify_again':
					$va_view=$this->add('xepan\base\View_User_VerifyAgain',array('options'=>$this->options));
					$this->app->stickyForget('options');
				break;

				case 'reset_form':
					$va_view=$this->add('xepan\base\View_User_ResetPassword',array('options'=>$this->options));
					$this->app->stickyForget('options');	
				break;

				case 'micro_login':					
				break;

				default:
					$this->add('View_Error')->set('View Not Found .....specify data-showview attr');	
			}
			
		}else{
			$this->js()->univ()->loaction($this->api->url($this->options['redirect_url']));
			if($this->options['layout'] == "micro_login")
				$this->add('xepan\base\View_User_MicroLogin',array('options'=>$this->options));
			else
				$this->add('xepan\base\View_User_AlreadyLoggedin',array('options'=>$this->options));
		}
	}

}