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

		$cols = $this->add('Columns');
		$l = $cols->addColumn(3)->addStyle('width','30%');
		$cols->addColumn(6);

		$l->add('xepan\base\View_Profile',['mode'=>'add'])->setModel('xepan\hr\Employee',['first_name','last_name','post_id']);
		
	}
}
