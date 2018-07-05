<?php

namespace xepan\base;


class Model_Config_Menus extends \xepan\base\Model_ConfigJsonModel{
	public $fields =[
						'value'=>'Text',
						'name'=>'Line',
						'is_set'=>'CheckBox'
					];
	public $config_key = 'CustomMenuSystem';
	public $application='base';

	function init(){
		parent::init();

		// XEC_DEFAULT is a reserve system name not alowed for custom menus
		// DEFAULT can only be saved just once (name should be unique anyway)
		// is_set defined if a menu is collection of other menus

	}

}