<?php

namespace xepan\base;

class Tool_UserPanel extends \xepan\cms\View_Tool{
	public $options = [
				'layout'=>'login_view',
			];	
	function init(){
		parent::init();
		$layout = $this->app->stickyGET('layout');
		if($layout){
			$this->options['layout']=$layout;
		}

		if(!$this->app->auth->isLoggedIn()){
			$view_url = $this->api->url(null,['cut_object'=>$this->name]);

			switch ($this->options['layout']) {
				case 'login_view':
					$user_login=$this->add('xepan\base\View_User_LoginPanel',array('options'=>$this->options));
					
					$user_login->on('click','a.forgotpassword',function($js,$data)use($view_url){
						return $this->js()->reload(['layout'=>'forget_password'],null,$view_url);
					});

					$user_login->on('click','a.reg_view',function($js,$data)use($view_url){
						return $this->js()->reload(['layout'=>'new_registration'],null,$view_url)->execute();
					});
					$this->app->stickyForget('layout');
				break;
				case 'forget_password':
					$f_view=$this->add('xepan\base\View_User_ForgotPassword',array('options'=>$this->options));
					
					$f_view->on('click','a.back-login',function($js,$data)use($view_url){
						return $this->js()->reload(['layout'=>'login_view'],null,$view_url)->execute();
					});

					$this->app->stickyForget('options');

				break;
				case 'new_registration':
					$r_view=$this->add('xepan\base\View_User_Registration',array('options'=>$this->options));
					$r_view->on('click','a.back-login',function($js,$data)use($view_url){
						return $this->js()->reload(['layout'=>'login_view'],null,$view_url)->execute();
					});

					$this->app->stickyForget('options');
				break;
			}
			
		}else{
			$this->add('View')->setHTML('Allready Logged In');
		}
	}

}