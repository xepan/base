<?php

namespace xepan\base;

class Form_Field_Rating extends  \xepan\base\Form_Field_DropDownNormal{
	
	function init(){
		parent::init();

	}

	function render(){
		$this->js(true)
			->_load('jquery.barrating.min')
			->_css('../js/barthemes/bootstrap-stars')
			->barrating([
				'theme'=> 'bootstrap-stars'
			]);

		parent::render();
	}

}