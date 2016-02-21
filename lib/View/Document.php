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

	public $many_to_show=[];
	public $view_fields=null;
	public $form_fields=null;

	public $form=null;

	function defaultTemplate(){
		if($this->action == 'view') 
			return $this->view_template;
		
		return parent::defaultTemplate();
	}


	function init(){
		parent::init();
		if(! $this->view_template) throw $this->exception("please provide template by 'view_template' option parameter");

		if($this->action == 'view')
			$this->form = new \Dummy();
		else{
			$this->form = $this->add('Form');
			$this->form->setLayout($this->view_template);
			$this->form->addSubmit('Save');
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
			$readonly_fields = array_diff($view_fields, $this->form_fields);
			foreach ($readonly_fields as $fld) {
				@$this->form->layout->template->trySet($fld,$model[$fld]);
			}
			return $m;
		}

		return parent::setModel($model,$fields);
	}

	function addMany(
			$model,
			$view_class='xepan\base\Grid',$view_options=null,$view_spot='Content',$view_defaultTemplate=null,$view_fields=null,
			$class='xepan\base\CRUD',$options=null,$spot='Content',$defaultTemplate=null,$fields=null
		)
	{

		$view_prefix = '';
		if($this->action=='view') $view_prefix='view_';

		$owner = $this;
		if($this->action != 'view') $owner = $this->form->layout;

		$v= $owner->add(
				${$view_prefix.'class'},
				${$view_prefix.'options'},
				${$view_prefix.'spot'},
				${$view_prefix.'defaultTemplate'}
			);
		$fields_2=null;
		if($this->action == 'view'){
			$fields_1 = $view_fields;
		}else{
			if(is_array($fields[0])){
				$fields_1 = $fields[0];
				$fields_2 = $fields[1];
			}else{
				$fields_1 = $fields[0];
			}
		}
		$v->setModel($model,$fields_1,$fields_2);
		$this->many_to_show[] = $v;
		return $v;
	}

	function recursiveRender(){

		if($this->action != 'view') {
			$this->form->onSubmit(function($f){	
				$f->save();
				return $this->js(null,$this->js()->univ()->notify('user','Saved','attached','bouncyflip'))->reload(['id'=>$f->model->id,'action'=>($this->action=='add'?'edit':$this->action)]);
				return $js;
			});	
		}

		return parent::recursiveRender();
	}


}
