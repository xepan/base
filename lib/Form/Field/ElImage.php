<?php

namespace xepan\base;


class Form_Field_ElImage extends \Form_Field_Line{
	function init(){
		parent::init();

		$btn=$this->afterField()->add('Button')->set('')->setIcon(' fa fa-file-image-o');
		$btn->js('click')->_load('elimage')->univ()->myelimage($this,'websites/'.$this->app->current_website_name.'/');
	}
}