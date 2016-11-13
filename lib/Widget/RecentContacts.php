<?php

namespace xepan\base;

class Widget_RecentContacts extends \xepan\base\Widget{
	function init(){
		parent::init();

		$this->grid = $this->add('Grid');
	}

	function recursiveRender(){
		$contact = $this->add('xepan\base\Model_Contact');
		$contact->setOrder('created_at','desc');

		$this->grid->setModel($contact,['name','created_at','created_by','type']);
		$this->grid->addPaginator(10);
		
		return parent::recursiveRender();
	}
}