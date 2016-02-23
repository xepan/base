<?php

/**
* description: Phone Numbers for Contact
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Model_Contact_Phone extends Model_Contact_Info{

	function init(){
		parent::init();
			
		$this->getElement('head')->enum(['Work','Personal','Mobile']);
		$this->addCondition('type','Phone');
	}
}
