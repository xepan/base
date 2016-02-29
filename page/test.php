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

class page_test extends \Page {
	public $title='Page Title';

	function init(){
		parent::init();
		$this->app->layout->destroy();
		$this->app->add('Layout_Fluid');
		$this->add('View')->set("Hello");
	}
}
