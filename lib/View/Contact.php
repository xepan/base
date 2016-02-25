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

class View_Contact extends \View{

	public $document_view=null;

	function init(){
		parent::init();

		// TODO : Check ACL here

		$this->document_view = $this->add('xepan\base\View_Document',
				[
					'action'=>$this->api->stickyGET('action')?:'view', // add/edit
					'id_fields_in_view'=>[],
					'allow_many_on_add' => false, // Only visible if editinng,
					'view_template' => ['view/contact']
				]
			);
		
	}

	function setModel(Model_Contact $contact){
		parent::setModel($contact);
		$this->document_view->setModel($this->model,null,['first_name','last_name']);
		if($this->model->loaded()){
			$this->document_view->addMany(
				$contact->ref('Emails'),
				$view_class='xepan\base\Grid',$view_options=null,$view_spot='Emails',$view_defaultTemplate=['view/contact','Emails'],$view_fields=null,
				$class='xepan\base\CRUD',$options=['grid_options'=>['defaultTemplate'=>['view/contact','Emails']]],$spot='Emails',$defaultTemplate=null,$fields=null
				);
		}else{
			$this->document_view->template->trySet('Emails','No Emails');
		}
		return $this->model;
	}

	
}
