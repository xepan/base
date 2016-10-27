<?php

/**
* description: Contact Info stores various info for any contact. 
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Model_Contact_Info extends Model_Table{
	public $table='contact_info';
	public $acl='parent';

	function init(){
		parent::init();

		// $this->hasOne('xepan\base\Epan');

		$this->hasOne('xepan\base\Contact');

		$this->addField('head');		
		$this->addField('value');

		$this->addField('type');

		$this->addField('is_active')->type('boolean')->defaultValue(true);
		$this->addField('is_valid')->type('boolean')->defaultValue(true); // Mark false if found hard bounced

		$this->is([
				'head|required'
		]);

		$this->addHook('beforeSave',$this);
	}

	function beforeSave($m){
    	$this->app->hook('contact_info',[$this]);    	
	}
}
