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

class Model_Epan extends \xepan\base\Model_Table{
	public $table='epan';

	function init(){
		parent::init();

		$this->hasOne('xepan\base\Epan_Category');		
		$this->addField('name')->hint('Identification for your epan');

		$this->hasMany('xepan\base\Epan_InstalledApplication',null,null,'InstalledApplications');
		$this->hasMany('xepan\communication\Epan_EmailSetting',null,null,'EmailSettings');
		
		$this->hasMany('xepan\base\Contact');
		$this->hasMany('xepan\base\User',null,null,'Users');
		$this->hasMany('xepan\base\Epan_Configuration',null,null,'Configurations');

		$this->addHook('beforeDelete',[$this,'deleteAllEmailSettings']);
		$this->addHook('beforeDelete',[$this,'deleteInstallApplications']);
		$this->addHook('beforeDelete',[$this,'deleteContacts']);
		$this->addHook('beforeDelete',[$this,'deleteUsers']);

		$this->is([
				'epan_category_id|required',
				'name|required|to_trim|unique'
			]);
	}

	function addActivity($contact_id, $activity, $related_contact_id=null, $related_document_id=null, $details=null){
		$activity = $this->add('xepan\base\Model_Activity');
		$activity['conatc_id'] = $contact_id;
		$activity['activity'] = $activity;
		$activity['related_contact_id'] = $related_contact_id;
		$activity['related_document_id'] = $related_document_id;
		$activity['details'] = $details;

		$activity->save();
		return $activity;
	}

	function deleteAllEmailSettings(){
		$this->ref('EmailSettings')->deleteAll();
	}

	function deleteInstallApplications(){
		$this->ref('InstalledApplications')->deleteAll();
	}

	function deleteContacts(){
		$this->ref('xepan\base\Contact')->deleteAll();
	}

	function deleteUsers(){
		$this->ref('Users')->deleteAll();
	}
}
