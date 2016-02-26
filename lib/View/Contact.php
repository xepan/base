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

		$action = $this->api->stickyGET('action')?:'view';
		$this->document_view = $this->add('xepan\base\View_Document',['action'=> $action,'id_field_on_reload'=>'contact_id'],null,['view/contact']);
		
	}

	function setModel(Model_Contact $contact){
		parent::setModel($contact);
		$this->document_view->setModel($this->model,null,['first_name','last_name']);
		if($this->model->loaded()){
			$e = $this->document_view->addMany('Emails',null,'Emails',['view/contact','Emails']);
			$e->setModel($contact->ref('Emails'));
		}else{
			$this->document_view->template->trySet('Emails','No Emails');
		}
		return $this->model;
	}

	
}
