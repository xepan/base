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

class Model_User extends Model_Contact{

	function init(){
		parent::init();
		
		$user_j = $this->join('user.contact_id');
		$user_j->addField('username');
		$user_j->addField('password')->type('password');

		$user_j->addField('is_active')->type('boolean')->defaultValue(true);

	}
}
