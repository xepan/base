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

class Component_Dummy extends \View{
	public $options = [];
	
	function init(){
		parent::init();
		$this->add('View')->set('Hello xEpan '.$this->options['text']);
	}
}
