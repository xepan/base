<?php

namespace xepan\base;


class Model_Config_ContactOtherInfo extends \xepan\base\Model_ConfigJsonModel{
	public $fields =[
						'for'=>'Line',
						'contact_other_info_fields'=>"Text",
					];
	public $config_key = 'Contact_Other_Info_Fields';
	public $application='base';

	function init(){
		parent::init();

		$this->getElement('for')->hint('Contact Type like Lead/Customer/Supplier/Affiliate etc');
	}

}