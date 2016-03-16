<?php
namespace xepan\base;

class Model_Mail_ResetPassword extends \xepan\base\Model_Mail_Content{
	function init(){
		parent::init();

		$this->addCondition('type','ResetPassword');
	}
}