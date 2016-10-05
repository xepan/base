<?php
namespace xepan\base;

class View_Activity extends \View{
	public $from_date;
	public $to_date;
	public $contact_id;

	function init(){
	parent::init();


	$activity_model=$this->add('xepan\base\Model_Activity');
	$activity_model->addExpression('contact_type',$activity_model->refSQL('related_contact_id')->fieldQuery('type'));
	
	if($this->from_date){
		$activity_model->addCondition('created_at','>=',$this->from_date);
	}
	if($this->to_date){
		$activity_model->addCondition('created_at','<',$this->app->nextDate($this->to_date));
	}
	if($this->contact_id){
		$activity_model->addCondition('related_contact_id',$this->contact_id);
	}

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
			case 'OutsourceParty':
				$contact_url='xepan_production_outsourcepartiesdetails'.'&contact_id='.$g->model['related_contact_id'];
				break;
			default:
				$contact_url='xepan_base_contactdetail';
		}
		$g->current_row['contact_url']= $contact_url;
	});

	$grid->addHook('formatRow',function($g){
		if(!$g->model['related_document_id'] && (strpos($g->model['activity'], 'Communicated') !== false) ) 
			$g->current_row_html['related_document_id'] = 'See Communication Detail';
		else
			if(!$g->model['related_document_id'])	
				$g->current_row_html['related_document_id'] = 'Not Available';

		if(!$g->model['related_contact_id']) $g->current_row_html['related_contact']= "Not Available";
	});

	$grid->addPaginator(10);
	$grid->addQuickSearch(['activity']);

	}
}
