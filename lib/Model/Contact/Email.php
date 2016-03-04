<?php

/**
* description: Emails for Contact
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Model_Contact_Email extends Model_Contact_Info{

	function init(){
		parent::init();
			
		$this->getElement('head')->enum(['Official','Personal']);
		$this->addCondition('type','Email');
		$this->is(['value|to_trim|required|email']);
	}
}
