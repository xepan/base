<?php

/**
* description: xEpan CMS Page runner. 
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class page_cms extends \Page {
	public $title='';

	function init(){
		parent::init();
		$this->add('View')->set("HAHA".rand(1000,9999))->js('click')->reload();		
	}
}
