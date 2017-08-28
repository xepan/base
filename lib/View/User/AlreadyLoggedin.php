<?php
namespace xepan\base;

class View_User_AlreadyLoggedin extends \View{
	public $options = [];

	function init(){
		parent::init();

		$auth = $this->app->auth;
		$auth->login($this->app->auth->model['username']);
		$this->app->hook('login_panel_user_loggedin',[$auth->model]);
	}
	
	function defaultTemplate(){
		return ['view/tool/userpanel/form/alreadyloggedin'];
	}
}	