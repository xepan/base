<?php

/**
* description: User Model used for Authentication basically. Not much is logged based on User Id
* Insted xEpan Platform throws an event and Application can hook this staff with any other Model like Staff
* and will log based on that model.
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Model_User extends \xepan\base\Model_Table{

	public $table="user";
	public $acl=true;

	public $actions=[
		'Active'=>['view','edit','delete','deactivate'],
		'InActive'=>['view','edit','delete','activate']
	];

	function init(){
		parent::init();
		
		$this->hasOne('xepan\base\Epan');

		$this->addField('username');
		$this->addField('password')->type('password');
		$this->addField('type');
		$this->addField('scope')->enum(['WebsiteUser','AdminUser','SuperUser'])->defaultValue('WebsiteUser');

		$this->addField('status')->enum(['Active','Inactive'])->defaultValue('Active');
		$this->addCondition('type','User');

		// $this->addHook('beforeSave',$this);
		// $this->addHook('beforeDelete',$this);
		$this->is([
			'username|to_trim|required|alphanum|to_lower|unique_in_epan'
			]);

	}

	// function beforeSave($m){
		
	// 	$old_user=$this->add('xepan\base\Model_User');
		
	// 	if($this->loaded())
	// 		$old_user->addCondition('id','<>',$this->id);
	// 	$old_user->tryLoadAny();

	// 	if($old_user['name'] == $this['name'])
	// 		throw $this->exception('User Name is Allready Taken');
	// }

	// function beforeDelete($m){}
}
