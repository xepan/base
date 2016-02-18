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

		// $cols = $this->add('Columns');
		// $l = $cols->addColumn(3)->addStyle('width','30%');
		// $cols->addColumn(6);
		$contact = $this->add('xepan\base\Model_Contact')->load($this->api->stickyGET('id'));
		$this->add('xepan\base\View_Profile',['action'=>$this->api->stickyGET('action')?:'view'])->setModel($contact,['first_name','last_name']);
		
	}
}
