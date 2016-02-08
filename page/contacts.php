<?php


namespace xepan\base;


class page_contacts extends \Page {

	public $title= "Contacts";

	function init(){
		parent::init();

		$contacts = $this->add('xepan\base\Model_Contact');
		$contacts->addExpression('email')->set($contacts->refSQL('Emails')->setLimit(1)->fieldQuery('email'));

		$userlist = $this->add('CompleteLister',null,'ContactList',['page\contacts','ContactListTemplate']);
		$userlist->setModel($contacts);

		$userlist->on('click','.user-link',$this->js()->univ()->location([$this->api->url('xepan_base_contact_profile'),'id'=>$this->js()->_selectorThis()->closest('tr')->data('id')]));

	}

	function defaultTemplate(){
		return ['page\contacts'];
	}
}