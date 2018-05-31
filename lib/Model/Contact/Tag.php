<?php

namespace xepan\base;

class Model_Contact_Tag extends \xepan\base\Model_ConfigJsonModel{
	public  $fields	= [
					'name'=>"Line"
				];

	public $config_key = 'XEPAN_BASE_CONTACT_TAG';
	public $application = 'base';

	function init(){
		parent::init();

		$this->addHook('beforeSave',[$this,'sanitize'],[],4);
	}

	function sanitize(){
		$this['name'] = "`".str_replace('`', "", trim($this['name']))."`";
	}
}