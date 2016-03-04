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
		$this->document_view->setModel($this->model,null,['first_name','last_name','address','city','state','country','pin_code','organization','post','website']);
		if($this->model->loaded()){
			$e = $this->document_view->addMany('Emails',null,'Emails',['view/addmany']);
			$e->setModel($contact->ref('Emails'));
			$e->template->tryDel('Pannel');

			$phone = $this->document_view->addMany('Phones',null,'Phones',['view/addmany']);
			$phone->setModel($contact->ref('Phones'));
			$phone->template->tryDel('Pannel');

			$im = $this->document_view->addMany('IMs',null,'IMs',['view/addmany']);
			$im->setModel($contact->ref('IMs'));
			$im->template->tryDel('Pannel');
			
			$event = $this->document_view->addMany('Events',null,'Events',['view/addmany']);
			$event->setModel($contact->ref('Events'));
			$event->template->tryDel('Pannel');

			$relation = $this->document_view->addMany('Relations',null,'Relations',['view/addmany']);
			$relation->setModel($contact->ref('Relations'));
			$relation->template->tryDel('Pannel');

		}
		return $this->model;
	}

	
}
