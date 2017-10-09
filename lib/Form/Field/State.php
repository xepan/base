<?php

namespace xepan\base;

class Form_Field_State extends  Form_Field_DropDown {

	public $id_field=null;
	public $title_field=null;
	public $include_status='Active'; // all, no condition
	public $country_field=null;

	function setIdField($id_field){
		$this->id_field = $id_field;
	}

	function setTitleField($title_field){
		$this->title_field = $title_field;
	}

	function includeAll(){
		$this->include_status=null;
	}

	function includeStatus($status){
		$this->include_status = $status;
	}

	function dependsOn($country_field){
		$this->country_field = $country_field;
	}

	function recursiveRender(){
		$contact = $this->add('xepan\base\Model_State');
		if($this->include_status) $contact->addCondition('status',$this->include_status);
		$this->setModel($contact,$this->id_field, $this->title_field);
		parent::recursiveRender();
	}

	function render(){
		if($this->country_field){
			$this->country_field->js('change')->univ()->alert('TODO');
		}
		return parent::render();
	}
}