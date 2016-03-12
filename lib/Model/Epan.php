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

		$this->hasMany('xepan\base\Epan_InstalledApplication',null,null,'InstalledApplications');
		$this->hasMany('xepan\base\Epan_EmailSetting',null,null,'EmailSettings');
		
		$this->hasMany('xepan\base\Contact');
		$this->hasMany('xepan\base\User',null,null,'Users');

		$this->addHook('beforeDelete',[$this,'deleteAllEmailSettings']);
		$this->addHook('beforeDelete',[$this,'deleteInstallApplications']);
		$this->addHook('beforeDelete',[$this,'deleteContacts']);
		$this->addHook('beforeDelete',[$this,'deleteUsers']);

		// $this->is([
		// 		'name|unique|to_trim|required'
		// 	]);
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

	function deleteEmailSettings(){
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
