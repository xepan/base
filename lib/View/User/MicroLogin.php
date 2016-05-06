<?php
namespace xepan\base;
class View_User_MicroLogin extends \View{
	function init(){
		parent::init();

		if($this->app->auth->isLoggedIn()){
			$this->template->tryDel('login_wrapper');
			$this->template->trySet('logout_url',$this->app->url($this->options['logout_page']));
			$this->setModel($this->app->auth->model);
		}
		else{
			$this->template->tryDel('logout_wrapper');
			$this->template->trySet('login_url',$this->app->url($this->options['login_page']));
		}


	}

	function defaultTemplate(){		
		return [$this->options['micro_login_layout']];
	}
}			