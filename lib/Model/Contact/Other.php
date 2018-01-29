<?php

/**
* description: IM for Contact
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Model_Contact_Other extends Model_Contact_Info{

	function init(){
		parent::init();

		$contact_other_info_config_m = $this->add('xepan\base\Model_ConfigJsonModel',
								[
									'fields'=>[
												'contact_other_info_fields'=>"Text",
												],
									'config_key'=>'Contact_Other_Info_Fields',
									'application'=>'base'
								]);
		$contact_other_info_config_m->tryLoadAny();
			
		$this->getElement('head')->enum(explode(",",$contact_other_info_config_m['contact_other_info_fields']));
		$this->addCondition('type','Other');
	}
}
