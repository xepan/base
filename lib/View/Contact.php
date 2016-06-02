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
	public $acl=null;
	public $document_view=null;
	public $vp ;


	function init(){
		parent::init();

		$page_url = $this->api->url();
		$this->vp = $this->add('VirtualPage');
		$this->vp->set(function($p)use($page_url){
			$f=$p->add('Form');
			$f->setModel('xepan\base\Contact',['image_id'])->load($this->api->stickyGET('contact_id'));
			$f->addSubmit('Save');
			if($f->submitted()){
				$f->save();
				$f->js()->univ()->location($page_url)->execute();
			}
		});

		$this->action = $action = $this->api->stickyGET('action')?:'view';
		$this->document_view = $this->add('xepan\base\View_Document',['action'=> $action,'id_field_on_reload'=>'contact_id'],null,['view/contact']);
		
	}

	function setModel(Model_Contact $contact){
		parent::setModel($contact);
		$this->document_view->setModel($this->model,null,['first_name','last_name','address','city','state_id','country_id','pin_code','organization','post','website']);
		if($this->action=='edit')
			$this->document_view->form->layout->add('xepan\base\Controller_Avatar',['extra_classes'=>'profile-img center-block','options'=>['size'=>200,'display'=>'block','margin'=>'auto'],'float'=>null,'model'=>$this->model]);
		else
			$this->document_view->add('xepan\base\Controller_Avatar',['extra_classes'=>'profile-img center-block','options'=>['size'=>200,'display'=>'block','margin'=>'auto'],'float'=>null]);

		if($this->model->loaded()){

			$email_m=$contact->ref('Emails');
			if($this->acl)
				$email_m->acl=$this->acl;
			$e = $this->document_view->addMany('Emails',null,'Emails',['view/addmany']);
			$e->setModel($email_m);
			$e->template->tryDel('Pannel');

			$phone_m=$contact->ref('Phones');
			if($this->acl)
				$phone_m->acl=$this->acl;
			$phone = $this->document_view->addMany('Phones',null,'Phones',['view/addmany']);
			$phone->setModel($phone_m);
			$phone->template->tryDel('Pannel');

			$ims_m=$contact->ref('IMs');
			if($this->acl)
				$ims_m->acl=$this->acl;
			$im = $this->document_view->addMany('IMs',null,'IMs',['view/addmany']);
			$im->setModel($ims_m);
			$im->template->tryDel('Pannel');
			
			$event_m=$contact->ref('Events');
			if($this->acl)
				$event_m->acl=$this->acl;			
			$event = $this->document_view->addMany('Events',null,'Events',['view/addmany']);
			$event->setModel($event_m);
			$event->template->tryDel('Pannel');
			$relation_m=$contact->ref('Relations');
			if($this->acl)
				$relation_m->acl=$this->acl;			
			$relation = $this->document_view->addMany('Relations',null,'Relations',['view/addmany']);
			$relation->setModel($relation_m);
			$relation->template->tryDel('Pannel');

		}


		return $this->model;
	}

	function recursiveRender(){
		$action = $this->api->stickyGET('action')?:'view';
		if($action == 'edit')
			$this->js('click')->_selector('.profile-img')->univ()->frameURL('Change Image',$this->vp->getURL());
		return parent::recursiveRender();
	}

	function format_datetime($fiels,$value,$m){
		$date = "<div>".date('d M Y',strtotime($value))."</div>";
		return $date;
	}

	
}
