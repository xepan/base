<?php

namespace xepan\base;

class Form_Field_State extends  Form_Field_DropDown {

	public $id_field=null;
	public $title_field=null;
	public $include_status='Active'; // all, no condition
	public $country_field=null;
	public $country_id=null;

	function init(){
		parent::init();
		$this->setEmptyText('Please Select');
	}

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
		$this->country_id = $_GET[$this->name.'_country_id'];
		$this->country_field->js('change',$this->form->js()->atk4_form('reloadField',$this->short_name,[$this->app->url(),$this->name.'_country_id'=>$this->country_field->js()->val()]));		// $this->country_field->js('change',$this->js()->reload(null,null,[$this->app->url(null,['cut_object'=>$this->name]),'country_id'=>$this->country_field->js()->val()]));
	}

	function recursiveRender(){
		$model = $this->add('xepan\base\Model_State');
		if($this->include_status) $model->addCondition('status',$this->include_status);
		if($this->country_id) $model->addCondition('country_id',$this->country_id);

		$this->setModel($model,$this->id_field, $this->title_field);
		return parent::recursiveRender();
	}

}