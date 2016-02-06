<?php

/**
* description: Contact serves as Base model for all models that relates to any human contact
* Let it be lead, customer, supplier or any other contact in any application.
* This contact model stores all basic possible details in this table and leave specific implementation
* for Model extending this Model by joining other tables
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Model_Contact extends \Model_Table{
	public $table='contact';

	function init(){
		parent::init();

		$this->hasOne('xepan\Base\Epan');
		
		$this->addField('first_name');
		$this->addField('last_name');

		$this->hasMany('xepan\base\Contact_Email');

	}
}
