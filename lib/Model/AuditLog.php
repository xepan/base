<?php
namespace xepan\base;
class Model_AuditLog extends Model_Table {
	var $table= "xepan_auditlog";
	function init(){
		parent::init();

		$this->hasOne('xepan\base\User','user_id')->defaultValue(@$this->api->auth->model->id);
		$this->hasOne('xepan\base\Contact','contact_id')->defaultValue(@$this->api->employee->id);
		
		$this->addField('model_class');
		$this->addField('pk_id')->type('int');

		$this->addField('created_at')->type('datetime')->defaultValue(date('Y-m-d H:i:s'));

		$this->addField('name')->type('text');
		$this->addField('type');

		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function logFieldEdit($model,$record_id,$edit_what_field,$old_value,$new_value){

	}
}