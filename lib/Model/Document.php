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
		$this->hasOne('xepan\base\Contact','created_by_id')->system(true)->defaultValue($this->app->employee->id);
		$this->hasOne('xepan\base\Contact','updated_by_id')->system(true);

		$this->addField('status')->enum($this->status)->mandatory(true)->system(true);
		$this->addField('type')->mandatory(true);
		$this->addField('sub_type')->system(true);

		$this->hasMany('xepan\base\Document_Attachment','document_id',null,'Attachments');
		$this->addExpression('attachments_count')->set($this->refSQL('Attachments')->count());

		$this->addField('created_at')->type('date')->defaultValue($this->app->now)->sortable(true);//->system(true);
		$this->addField('updated_at')->type('date')->defaultValue($this->app->now)->sortable(true);//->system(true);

		$this->is([
				'created_at|required'
			]);

	}
}
