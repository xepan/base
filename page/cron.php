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

class page_cron extends \Page {
	public $title='Cron Job Collector and Executor';

	function init(){
		parent::init();
		
		$resolver = new \Cron\Resolver\ArrayResolver();

		$this->app->hook('cron_exector',[$resolver]);
	}
}
