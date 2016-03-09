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

		$this->addHook('beforeSave',$this);
		$this->addHook('beforeDelete',$this);
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

	function beforeSave($m){
		
		$epan_old=$this->add('xepan\base\Model_Epan');
		
		if($this->loaded())
			$epan_old->addCondition('id','<>',$this->id);
		$epan_old->tryLoadAny();

		if($epan_old['name'] == $this['name'])
			throw $this->exception('Epan Name is Allready Exist');
	}


	function beforeDelete($m){
		$install_comp_count = $m->ref('InstalledApplications')->count()->getOne();
		$email_setting_count = $m->ref('EmailSettings')->count()->getOne();
		$contact_count = $m->ref('xepan\base\Contact')->count()->getOne();
		
		if($install_comp_count or $email_setting_count or $contact_count)
			throw $this->exception('Cannot Delete,first delete InstalledApplications,EmailSettings, Contacts ');	
	}
}
