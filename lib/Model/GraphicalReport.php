<?php


namespace xepan\base;

class Model_GraphicalReport extends \xepan\base\Model_Table{
	public $table='graphical_report';

	public $acl_type='GraphicalReport';

	public $status=['All'];
	public $actions=['All'=>['view','edit','delete','manage_widgets','manage_post_permissions']];

	function init(){
		parent::init();

		$this->addField('name');
		// allowed to which post :: hasmany or json {"12","23"} like id search  
		// 

		$this->hasMany('xepan\base\GraphicalReport_Widget','graphical_report_id');

		$this->addExpression('status','"All"');
	}
}