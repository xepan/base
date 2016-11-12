<?php


namespace xepan\base;

class Model_GraphicalReport_Widget extends \xepan\base\Model_Table{

	public $table='graphical_report_widget';
	function init(){
		parent::init();

		$this->hasOne('xepan\base\GraphicalReport','graphical_report_id');
		$this->addField('name');
		// allowed to which post :: hasmany or json {"12","23"} like id search  
		$this->addField('class_path');
	}
}