<?php

namespace xepan\base;

class Initiator extends \Controller_Addon {
	public $addon_name = 'xepan_base';

	function init(){
		parent::init();
		$this->routePages('xepan_base');
		$this->addLocation(array('template'=>'templates'));
	}
}
