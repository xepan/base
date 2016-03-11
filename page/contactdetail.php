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

class page_contactdetail extends \Page {
	public $title='Contact Detail';

	function init(){
		parent::init();

		$contact = $this->add('xepan\base\Model_Contact')->load($this->api->stickyGET('contact_id'));

		$contact_view = $this->add('xepan\base\View_Contact');
		$contact_view->setModel($contact);

		
	}
}
