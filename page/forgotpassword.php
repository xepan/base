<?php
namespace xepan\base;
class page_forgotpassword extends \Page{
	public $title="Forgot Password";
	function init(){
		parent::init();
		$this->add('xepan\base\View_User_ForgotPassword');
	}
}