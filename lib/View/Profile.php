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

class View_Profile extends \View{

	public $action = 'view'; // add/edit

	function setModel($model,$fields=null){
		parent::setModel($model,$fields);
		if($this->action != 'view'){			
			$form = $this->add('Form');
			$form->setLayout(['view/profile']);
			$form->setModel($this->model,$fields);

			$form->onSubmit(function($f){
				$f->save();
				return $f->js()->reload(['id'=>$f->model->id,'action'=>'edit']);
			});
			if($this->model->loaded())
				$form->layout->add('CRUD',[
						'grid_class'=>'xepan\base\Grid',
						'grid_options'=>['template_option'=>['view/profile','Emails']]
						],'Emails')
					->setModel($this->model->ref('Emails'));
		}else{
			$this->add('Grid',['show_header'=>false],'Emails',['view/profile','Emails'])->setModel($this->model->ref('Emails'),['email']);
		}

		
	}

	function defaultTemplate(){
		if($this->action=='view')
			return ['view/profile'];
		else
			return parent::defaultTemplate();
	}
}
