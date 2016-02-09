<?php

/**
* description: ATK Page
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class page_contact_profile extends \Page {
	public $title='Contact Profile';

	function init(){
		parent::init();

		
		// $this->add('xepan\base\View_Profile')->setModel($this->api->auth->model);

		$form = $this->add('Form');
		$form->setLayout(['view/profile']);
		$form->setModel($this->api->auth->model->reload(),['first_name','last_name','type']);

		$form->onSubmit(function($f){
			// return $f->displayError('first_name','HELLO');
			$f->save();
			return $f->js()->reload();
		});
		
	}
}
