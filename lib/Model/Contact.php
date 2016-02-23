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

class Model_Contact extends \xepan\base\Model_Table{
	public $table='contact';

	function init(){
		parent::init();

		$this->hasOne('xepan\base\Epan');
		$this->addField('type');
		
		$this->addField('first_name');
		$this->addField('last_name');
		$this->addField('is_active')->type('boolean')->defaultValue(true);

		$this->addField('address')->type('text');
		$this->addField('city');
		$this->addField('state');
		$this->addField('country');
		$this->addField('pin_code');

		$this->addExpression('status')->set($this->dsql()->expr('IF([0]=1,"Active","InActive")',[$this->getElement('is_active')]));
		$this->addExpression('name')->set($this->dsql()->expr('CONCAT([0]," ",[1])',[$this->getElement('first_name'),$this->getElement('last_name')]));

		$this->hasMany('xepan\base\Contact_Email',null,null,'Emails');
		$this->hasMany('xepan\base\Contact_Phone',null,null,'Phones');
		$this->hasMany('xepan\base\Contact_Relation',null,null,'Relations');

		// $this->addExpression('email')->set($this->refSQL('Emails')->setLimit(1)->fieldQuery('email'));

	}
}
