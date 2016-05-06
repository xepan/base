<?php
namespace xepan\base;
class View_User_MicroLogin extends \View{
	function init(){
		parent::init();

		if($this->app->auth->isLoggedIn()){
			$this->template->tryDel('login_wrapper');
			$this->template->trySet('logout_url','/?page=logout');
			$this->setModel($this->app->auth->model);
		}
		else
			$this->template->tryDel('logout_wrapper');


	}

	function defaultTemplate(){		
		return [$this->options['micro_login_layout']];
	}
}			