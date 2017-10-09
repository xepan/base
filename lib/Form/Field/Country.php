<?php

namespace xepan\base;

class Form_Field_Country extends  Form_Field_DropDown {

	public $validate_values = false;
	public $id_field=null;
	public $title_field=null;
	public $include_status='Active'; // all, no condition

	public $editing_in_crud=false;
	function init(){
		parent::init();

		$form = $this->owner;
		if(!($this->owner instanceof \Form)){
			$form= $this->owner->owner ; // looks like layout added
		}
		if($form->owner instanceof \CRUD){
			if($form->owner->isEditing('edit')) $this->editing_in_crud = true;
		}
		$this->setEmptyText('Please Select');
	}
	
	function setIdField($id_field){
		$this->id_field = $id_field;
		return $this;
	}

	function setTitleField($title_field){
		$this->title_field = $title_field;
		return $this;
	}

	function includeAll(){
		$this->include_status=null;
		return $this;
	}

	function includeStatus($status){
		$this->include_status = $status;
		return $this;
	}

	function recursiveRender(){
		$model = $this->add('xepan\base\Model_Country');
		if(!$this->editing_in_crud and $this->include_status) $model->addCondition('status',$this->include_status);
		$this->setModel($model,$this->id_field, $this->title_field);
		parent::recursiveRender();
	}
}