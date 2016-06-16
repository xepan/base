<?php

namespace xepan\base;

class Model_State extends \xepan\base\Model_Table{

	public $table="state";
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
		
		$this->hasOne('xepan\base\Country','country_id');
		$this->hasOne('xepan\hr\Employee','created_by_id')->defaultValue(@$this->app->employee->id);

		$this->addField('status')->enum(['Active','InActive'])->defaultValue('Active');
		
		$this->addField('name');		
		$this->addField('type');
		$this->addField('abbreviation');
		$this->addCondition('type','State');
		$this->addHook('beforeDelete',$this);

		$this->is([
				'name|to_trim|required|unique_in_epan'
			]);
	}

	function beforeDelete($m){
		if($m['name'] === 'All'){
			throw new \Exception("Cannot delete states together, please delete states individually");
		}		
	}
}