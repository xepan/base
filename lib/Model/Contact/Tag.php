<?php

namespace xepan\base;

class Model_Contact_Tag extends \xepan\base\Model_ConfigJsonModel{
	public  $fields	= [
					'name'=>"Line"
				];

	public $config_key = 'XEPAN_BASE_CONTACT_TAG';
	public $application = 'base';
}