<?php

namespace xepan\base;


class Widget extends \View{

	public $filter_form = null;
	public $report=null;

	function init(){
		parent::init();
		$this->set('Base Widget');
	}

	function setFilterForm($form){
		$this->filter_form = $form;
		return $this;
	}

}