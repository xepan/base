<?php

/**
* description: ATK Model
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Model_Activity extends xepan\base\Model_Table{
	public $table='activity';

	function init(){
		parent::init();

		$this->hasOne('xepan\base\Epan');
		$this->hasOne('xepan\base\Contact');
		$this->hasOne('xepan\base\Contact','related_contact_id');
		$this->hasOne('xepan\base\Document','related_document_id');

		$this->addField('activity');
		$this->addField('details');

		$this->addField('created_at')->type('datetime')->defaultValue($this->api->now);
	}
}
