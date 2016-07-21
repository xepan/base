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

class Model_Activity extends Model_Table{
	public $table='activity';

	function init(){
		parent::init();

		$this->hasOne('xepan\base\Epan');
		$this->hasOne('xepan\base\Contact');
		$this->hasOne('xepan\base\Document','related_document_id');
		$this->hasOne('xepan\base\Contact','related_contact_id');

		$this->addField('activity');
		$this->addField('details');
		$this->addField('notify_to');
		$this->addField('notification');

		$this->addField('created_at')->type('datetime')->defaultValue($this->api->now);
		$this->setOrder('created_at','desc');		
		$this->add('misc/Field_Callback','callback_date')->set(function($m){
			if(date('Y-m-d',strtotime($m['created_at']))==date('Y-m-d',strtotime($this->app->now))){
				return date('h:i a',strtotime($m['created_at']));	
			}
			return date('M d y',strtotime($m['created_at']));
		});

	}
	
}
