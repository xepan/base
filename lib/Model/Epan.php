<?php

/**
* description: Epan Model is the core of a system
* Epan in xEpan can be considered as a Website Hosted in cloud
* each Epan has its own isolated entities in the xEpan platform.
* for single ERP or Website developement this is a mandotory singlaton entity
* @author : Gowrav Vishwakarma
* 
*/

namespace xepan\base;

class Model_Epan extends \Model_Table{
	public $table='epan';

	function init(){
		parent::init();

		$this->hasOne('xepan\base\Epan_Category');		
		$this->addField('name')->mandatory(true)->hint('Identification for your epan');

		$this->hasMany('Epan_InstalledApplication',null,null,'InstalledApplications');
		$this->hasMany('Epan_EmailSetting',null,null,'EmailSettings');
		
		$this->hasMany('xepan\base\Contact');
	}
}
