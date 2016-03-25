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
		$this->addField('hash');
		$this->addField('status')->enum(['Active','Inactive'])->defaultValue('Active');
		$this->addCondition('type','User');
		$this->hasMany('xepan\base\Contact','user_id',null,'Contacts');
		$this->is([
				'username|unique|to_trim|required|email'
			]);

	}

	function isSuperUser(){
		return $this['scope']=='SuperUser';
	}

	// function createNewCustomer($first_name,$last_name,$email){
	// 	$customer=$this->add('xepan\commerce\Model_Customer');
	// 	$customer['epan_id']=$this->app->auth->model->ref('epan_id')->id;
	// 	$customer['user_id']=$this->id;
	// 	$customer['first_name']=$first_name;
	// 	$customer['last_name']=$last_name;
	// 	$customer->save();
	// 	$email_model=$customer->ref('Emails');
	// 	$email_model['head']='Official';
	// 	$email_model['value']=$email;
	// 	$email_model->save();
	// }
}
