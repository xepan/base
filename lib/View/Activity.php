<?php
namespace xepan\base;

class View_Activity extends \View{

	function init(){
	parent::init();

	$activity_model=$this->add('xepan\base\Model_Activity');
	$activity_model->addExpression('contact_type',$activity_model->refSQL('related_contact_id')->fieldQuery('type'));
	
	$grid = $this->add('xepan\base\Grid',null,null,['view/activity/activities']);
	$grid->setModel($activity_model);

	$grid->addHook('formatRow',function($g){
		switch($g->model['contact_type']){
			case 'Lead':
				$contact_url='xepan_marketing_leaddetails'.'&contact_id='.$g->model['related_contact_id'];
				break;
			case 'Customer':
				$contact_url='xepan_commerce_customerdetail'.'&contact_id='.$g->model['related_contact_id'];
				break;
			case 'Supplier':
				$contact_url='xepan_commerce_supplierdetail'.'&contact_id='.$g->model['related_contact_id'];
				break;
			case 'Employee':
				$contact_url='xepan_hr_employeedetail'.'&contact_id='.$g->model['related_contact_id'];
				break;
			default:
				$contact_url='xepan_base_contactdetail';
		}
		$g->current_row['contact_url']= $contact_url;
	});
	$grid->addPaginator(10);

	}
}
