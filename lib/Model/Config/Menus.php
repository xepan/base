<?php

namespace xepan\base;


class Model_Config_Menus extends \xepan\base\Model_ConfigJsonModel{
	public $fields =[
						'value'=>'Text',
						'name'=>'Line',
						'related_with'=>'Line',
						'related_with_id'=>'Line',
					];
	public $config_key = 'CustomMenuSystem';
	public $application='base';

	function init(){
		parent::init();

		// XEC_DEFAULT is a reserve system name not alowed for custom menus
		// related_with/id can be used mostly by post_id if acceced from HR 

	}

}