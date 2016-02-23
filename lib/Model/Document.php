<?php

/**
* description: Document is a global model for almost all documents in xEpan platform.
* Main purpose of document model/table is to give a system wide unique id for all documents spreaded 
* in various tables.
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Model_Document extends \xepan\base\Model_Table{
	
	public $table='document';

	public $status=[];
	public $actions=[];

	function init(){
		parent::init();
		
		$this->hasOne('xepan\base\Epan');
		$this->hasOne('xepan\base\Contact','created_by_id')->editable(false);
		$this->hasOne('xepan\base\Contact','updated_by_id')->editable(false);

		$this->addField('status')->enum($this->status)->mandatory(true)->editable(false);
		$this->addField('type')->mandatory(true);

		$this->addField('created_at')->type('datetime')->defaultValue($this->app->now)->editable(false);
		$this->addField('updated_at')->type('datetime')->defaultValue($this->app->now)->editable(false);

	}
}
