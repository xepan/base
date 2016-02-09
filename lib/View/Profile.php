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

class View_Profile extends \View{

	function init(){
		parent::init();
		
	}

	function defaultTemplate(){
		return ['view/profile'];
	}
}
