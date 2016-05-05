<?php
namespace xepan\base;

class page_registration extends \Page{
	public $title="Verify Email";
	function init(){
		parent::init();
		$this->add('xepan\base\View_User_VerifyAccount');
	}
}