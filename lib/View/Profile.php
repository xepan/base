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

	public $mode = 'view'; // add/edit

	function setModel($model,$fields=null){
		parent::setModel($model,$fields);
		if($this->mode != 'view'){			
			$form = $this->add('Form');
			$form->setLayout(['view/profile']);
			$form->setModel($this->model,$fields);

			$form->onSubmit(function($f){
				$f->save();
				return $f->js()->reload();
			});
		}
		
	}

	function defaultTemplate(){
		if($this->mode=='view')
			return ['view/profile'];
		else
			return parent::defaultTemplate();
	}
}
