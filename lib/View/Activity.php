<?php
namespace xepan\base;

class View_Activity extends \View{

	function init(){
	parent::init();

	$contact_array = [
						"Lead" => "xepan_marketing_leaddetails",
						"Customer" => "xepan_commerce_customerdetail",
						"Supplier" => "xepan_commerce_supplierdetail",
						"Employee" => "xepan_hr_employeedetail"
					];


	$activity_model=$this->add('xepan\base\Model_Activity');
	$activity_model->addExpression('contact_type',$activity_model->refSQL('related_contact_id')->fieldQuery('type'));
	
	$grid = $this->add('xepan\base\Grid',null,null,['view/activity/activities']);
	$grid->setModel($activity_model);

	$grid->js('click')->_selector('.do-view-person-frame')->univ()->frameURL('Customer Details',[$this->api->url('xepan_commerce_customerdetail'),'contact_id'=>$this->js()->_selectorThis()->closest('[data-contact-id]')->data('contact-id')]);


	}
}
