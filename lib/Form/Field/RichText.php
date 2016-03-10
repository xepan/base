<?php


namespace xepan\base;

class Form_Field_RichText extends \Form_Field_Text{
	public $options=array();

	function init(){
		parent::init();
		$this->addClass('tinymce');
	}

	function render(){

		$this->js(true)
				->_load('tinymce/tinymce.min')
				->_load('tinymce/jquery.tinymce.min')
				->_load('xepan-richtext');
		$this->js(true)->univ()->richtext($this,$this->options);
		parent::render();
	}
}