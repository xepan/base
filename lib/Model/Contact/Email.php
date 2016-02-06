<?php

/**
* description: ContactEmail stores email ids for any contact. 
* Cosidering Digital Age, xEpan platform considers email as primary communication channel and hence 
* Pay proper attention for emails
* These emails will also be used for primary identification for any contact
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Model_Contact_Email extends \Model_Table{
	public $table='email';

	function init(){
		parent::init();

		$this->hasOne('xepan\base\Contact');

		$this->addField('email')->mandatory(true);

		$this->addField('is_valid')->type('boolean')->defaultValue(true); // Mark false if found hard bounced
	}
}
