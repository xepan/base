<?php

namespace xepan\base;


class Widget extends \View{

	public $filter_form = null;
	public $report=null;

	public $chart=null;

	function init(){
		parent::init();
		$this->set('Base Widget');
	}

	function setFilterForm($form){
		$this->filter_form = $form;
		return $this;
	}

	function recursiveRender(){
		if($this->chart){
			$this->chart->onRender($this->js()->masonry(['itemSelector'=>'.widget'])->_selector('.widget-grid')->_enclose());
		}
		return parent::recursiveRender();
	}

}