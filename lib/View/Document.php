<?php

/**
* description: View_Document is special View that helps to edit 
* any View in its own template by using same template as form layout
* It also helps in managing hasMany relations to be Viewed and Edit on same Level
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class View_Document extends \View{

	public $view_template = null;
	public $action='view'; // add/edit
	public $id_fields_in_view=[];
	public $allow_many_on_add=true;

	public $many=[];
	public $view_fields=null;
	public $form_fields=null;

	public $form=null;
	public $id_field_on_reload='id';
	public $submit_button="Save";

	function init(){
		parent::init();
		// if(! $this->view_template) throw $this->exception("please provide template by 'view_template' option parameter");

		if($this->action == 'view')
			$this->form = new \Dummy();
		else{
			$ot = clone $this->template;
			$this->template->loadTemplateFromString('{$Content}');
			$this->form = $this->add('Form');
			$this->form->setLayout($ot);
			$this->form->addSubmit($this->submit_button);
		}
	}

	function setModel($model,$view_fields=null,$form_fields=null){
		
		$this->view_fields = $view_fields;
		$this->form_fields = $form_fields;
		
		if($this->action=='view'){
			$fields = $view_fields;
		}
		else{
			$fields = $form_fields;
			$m = $this->form->setModel($model,$this->form_fields);

			/* Still NonEditable fields should show as on view mode */
			
			$view_fields = $view_fields?:$m->getActualFields();
			$readonly_fields = array_diff($view_fields, $this->form_fields?:[]);
			foreach ($readonly_fields as $fld) {
				@$this->form->layout->template->trySet($fld,$model[$fld]);
			}
			parent::setModel($model,$view_fields);			
			return $m;
		}

		return parent::setModel($model,$fields);
	}

	function modelRender()
    {
    	foreach ($this->model->get() as $field => $value) {
			if($this->owner->hasMethod('format_'.$field)){
				$value = $this->owner->{'format_'.$field}($value,$this->model);
			}elseif($this->owner->hasMethod('format_'.$this->model->getElement($field)->type())){
				$value = $this->owner->{'format_'.$this->model->getElement($field)->type()}($field,$value,$this->model);
			}elseif($this->hasMethod('format_'.$this->model->getElement($field)->type())){
				$value = $this->{'format_'.$this->model->getElement($field)->type()}($field,$value,$this->model);
			}
			$this->template->trySetHTML($field,$value);
		}
    }

	function addMany($entity,$options=null,$spot=null,$template=null) {
		$class = 'xepan\hr\CRUD';

		if($this->action=='view'){
			$class='xepan\base\Grid';
			$base = $this;
		}else{
			$base = $this->form->layout;
		}

		$v = $base->add($class,$options,$spot,$template);
		return $this->many[$entity] = $v;
		
	}

	function recursiveRender(){

		if($this->action != 'view') {
			$this->form->onSubmit(function($f){	
				$f->save();
				return $this->js(null,$this->js()->univ()->notify('user','Saved','attached','bouncyflip'))->univ()->location($this->api->url(null,[$this->id_field_on_reload=>$f->model->id,'action'=>($this->action=='add'?'edit':$this->action)]));
				return $js;
			});	
		}

		return parent::recursiveRender();
	}

	// Formats
	

	function format_boolean($field,$value,$m){
		$icon = $value?'check-circle':'times-circle';
		$color = $value?'green':'red';
		return "<i class='fa fa-$icon status-$color'> $field</i>";
	}


}
