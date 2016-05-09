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
	public $status=[
	'Active',
	'InActive'
	];
	public $actions=[
		'Active'=>['view','edit','delete','deactivate'],
		'InActive'=>['view','edit','delete','activate']
	];

	function init(){
		parent::init();
		
		$this->hasOne('xepan\base\Epan');
		$this->hasOne('xepan\base\Contact','created_by_id');

		$this->addField('username')->sortable(true);
		$this->addField('password')->type('password');
		$this->addField('type');
		$this->addField('scope')->enum(['WebsiteUser','AdminUser','SuperUser'])->defaultValue('WebsiteUser');
		$this->addField('hash');
		$this->addField('last_login_date')->type('datetime');
		$this->addField('status')->enum(['Active','Inactive'])->defaultValue('Active');
		$this->addCondition('type','User');
		$this->hasMany('xepan\base\Contact','user_id',null,'Contacts');
		$this->is([
				'username|unique|to_trim|required|email'
			]);

		// $this->app->auth->addEncryptionHook($this);


		
		$this->addExpression('related_contact')->set(function($m,$q){
			return $m->refSQL('Contacts')->setLimit(1)->fieldQuery('name');
		})->sortable(true);

		$this->addExpression('related_contact_type')->set(function($m,$q){
			return $m->refSQL('Contacts')->setLimit(1)->fieldQuery('type');
		})->sortable(true);

	}

	function isSuperUser(){
		return $this['scope']=='SuperUser';
	}

	function isAdminUser(){
		return $this['scope'] == 'AdminUser';
	}

	function updatePassword($new_password){
		if(!$this->loaded()) return false;
			$this['password']=$new_password;
			$this->save();
			return $this;
	}

	function deactivate(){
		$this['status']='InActive';
		$this->save();
	}

	function activate(){
		$this['status']='Active';
		$this->save();
	}

	function createNewCustomer($first_name,$last_name,$user_id){
		$customer=$this->add('xepan\commerce\Model_Customer');
		$customer['first_name']=$first_name;
		$customer['last_name']=$last_name;
		$customer['user_id']=$user_id;
		$customer->save();
	}
}
