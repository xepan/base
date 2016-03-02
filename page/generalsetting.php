<?php
namespace xepan\base;

class page_generalsetting extends \Page{
	public $title="General Settings";
	function init(){
		parent::init();

		$crud=$this->add('xepan\hr\CRUD',null,'general_setting');
		$crud->setModel('xepan\base\Epan_EmailSetting');

		$this->add('xepan\base\View_Emails',null,'email');
	}
	function defaultTemplate(){
		return ['page/general-setting'];
	}
}