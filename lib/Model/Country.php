<?php

namespace xepan\base;

class Model_Country extends \xepan\base\Model_Table{

	public $table="country";
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
		
		$this->hasOne('xepan\hr\Employee','created_by_id')->defaultValue($this->app->employee->id);

		$this->addField('status')->enum(['Active','InActive'])->defaultValue('Active');

		$this->addField('name');
		$this->addField('iso_code');

		$this->addField('type');
		$this->addCondition('type','Country');
		
		$this->hasMany('xepan\base\State','country_id');
	}
}