<?php

/**
* description: xEPAN Grid, lets you defined template by options
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Grid extends \Grid{

	public $template_option=null;

	function defaultTemplate(){
		if($this->template_option) return $this->template_option;
		return parent::defaultTemplate();
	}
}
