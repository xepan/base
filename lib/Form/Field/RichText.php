<?php


namespace xepan\base;

class Form_Field_RichText extends \Form_Field_Text{
	
	function render(){
		$this->js(true)->
			_load('bootstrap-wysiwyg')->
			_load('xepan-richtext')->
			univ()->richtext();
		parent::render();
	}
}