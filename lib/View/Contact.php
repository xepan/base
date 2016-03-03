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
		$this->document_view->setModel($this->model,null,['name']);
		if($this->model->loaded()){
			$e = $this->document_view->addMany('Emails',null,'Emails',['view/addmany']);
			$e->setModel($contact->ref('Emails'),['value']);

			$phone = $this->document_view->addMany('Phones',null,'Phones',['view/addmany']);
			$phone->setModel($contact->ref('Phones'),['value']);
		}else{
			$this->document_view->template->trySet('Emails','No Emails');
			$this->document_view->template->trySet('Phones','No Phones');
		}
		return $this->model;
	}

	
}
