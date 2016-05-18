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
		$this->hasOne('xepan\base\Contact','created_by_id');		
		
		$this->addField('name')->hint('Identification for your epan');
		$this->addField('type')->defaultValue('epan')->system(true);
		$this->addField('status')->defaultValue('Trial');
		$this->addField('created_at')->type('datetime')->defaultValue(isset($this->app->now)?$this->app->now:null)->system(true);

		$this->hasMany('xepan\base\Epan_InstalledApplication',null,null,'InstalledApplications');
		$this->hasMany('xepan\communication\Epan_EmailSetting',null,null,'EmailSettings');
		
		$this->hasMany('xepan\base\Contact');
		$this->hasMany('xepan\base\User',null,null,'Users');
		$this->hasMany('xepan\base\Epan_Configuration',null,null,'Configurations');

		$this->addHook('beforeSave',[$this,'createFolder']);
		$this->addHook('afterInsert',[$this,'createSuperUser']);
		$this->addHook('beforeDelete',[$this,'deleteAllEmailSettings']);
		$this->addHook('beforeDelete',[$this,'deleteInstallApplications']);
		$this->addHook('beforeDelete',[$this,'deleteContacts']);
		$this->addHook('beforeDelete',[$this,'deleteUsers']);


		$this->is([
				'epan_category_id|required',
				'name|required|to_trim|unique'
			]);
	}

	function createFolder($m){
		if(file_exists(realpath($this->app->pathfinder->base_location->base_path.'/websites/'.$this['name']))){
			throw $this->exception('Epan cannot be created, folder already exists','ValidityCheck')
						->setField('name')
						->addMoreInfo('epan',$this['name']);
		}
		$fs = \Nette\Utils\FileSystem::createDir('./websites/'.$this['name']);		
	}

	function createSuperUser($m,$new_id){
		$user = $this->add('xepan\base\Model_User_SuperUser');
        $this->app->auth->addEncryptionHook($user);
        $user=$user->set('username','admin'.$new_id.'@epan.in')
             ->set('scope','SuperUser')
             ->set('password','admin')
             ->set('epan_id',$new_id)
             ->saveAndUnload('xepan\base\Model_User_Active');
        $this->app->hook('epan-created',[$new_id]);
	}


	function installApp($application){
		$installed  = $this->add('xepan\base\Model_InstalledApplications')
								->addCondition('epan_id',$this->id)
								->addCondition('application_id',$application->id)
								->tryLoadAny();
		if($installed->loaded())
			throw $this->exception('Application Already Installed','ValidityCheck')
						->setField('application_id')
						->addMoreInfo('App',$application['name']);


		$installed->save();

	}

	function addActivity($contact_id, $activity, $related_contact_id=null, $related_document_id=null, $details=null){
		$activity = $this->add('xepan\base\Model_Activity');
		$activity['contact_id'] = $contact_id;
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
