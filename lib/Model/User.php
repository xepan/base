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

	function init(){
		parent::init();
		
		$this->hasOne('xepan\base\Epan');

		$this->addField('username');
		$this->addField('password')->type('password');

		$this->addField('type');
		$this->addField('scope')->enum(['WebsiteUser','AdminUser','SuperUser'])->defaultValue('WebsiteUser');

		$this->addField('status')->enum(['Active','Inactive'])->defaultValue('Active');
		$this->addCondition('type','User');

	}
}
