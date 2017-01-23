<?php


namespace xepan\base;

class Form_Field_CodeEditor extends \Form_Field_Text{
	public $options=array();
	public $lang='html';
	public $theme='tomorrow';

	function init(){
		parent::init();
	}

	function render(){
		$this->js(true)
				->_load('ace/ace/ace')
				->_load('ace/ace/mode-'.$this->lang)
				->_load('ace/ace/theme-'.$this->theme)
				->_load('ace/jquery-ace.min');

		$this->js(true)->ace(['theme' =>$this->theme, 'lang'=>$this->lang]);
		parent::render();
	}
}