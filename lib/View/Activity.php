<?php
namespace xepan\base;

class View_Activity extends \View{
	public $from_date;
	public $to_date;
	public $contact_id;
	public $related_person_id;
	public $department_id;
	public $communication_type;
	public $descendants = 'descendants';
	public $self_activity;

	function init(){
		parent::init();
			
		$activity_model=$this->add('xepan\base\Model_Activity');
		$activity_model->addExpression('contact_type',$activity_model->refSQL('related_contact_id')->fieldQuery('type'));
		$activity_model->addExpression('department')->set(function($m,$q){
			$employee = $this->add('xepan\hr\Model_Employee');
			$employee->addCondition('id',$m->getField('contact_id'));
			$employee->setLimit(1);
			return $employee->fieldQuery('department_id');
		});

		$activity_model->addExpression('post')->set(function($m,$q){
			$employee = $this->add('xepan\hr\Model_Employee');
			$employee->addCondition('id',$m->getElement('contact_id'));
			$employee->setLimit(1);
			return $employee->fieldQuery('post_id');
		});
		
		// $activity_model->addCondition('post',array_unique($this->descendants));

		if($this->self_activity == 'true'){											
			$activity_model->addCondition('contact_id','<>',$this->app->employee->id);			
		}
		if($this->from_date){
			$activity_model->addCondition('created_at','>=',$this->from_date);			
		}
		if($this->to_date){
			$activity_model->addCondition('created_at','<',$this->app->nextDate($this->to_date));
		}
		if($this->contact_id){
			$activity_model->addCondition('contact_id',$this->contact_id);
		}
		if($this->related_person_id){
			$activity_model->addCondition('related_contact_id',$this->related_person_id);
		}
		if($this->department_id){
			$activity_model->addCondition('department',$this->department_id);
		}

		if($this->communication_type){						
			$activity_comm_j = $activity_model->join('communication.from_id','contact_id');
			$activity_comm_j->addField('communication_type');
			$activity_model->addCondition('communication_type',$this->communication_type);
			$activity_model->_dsql()->group($activity_model->dsql()->expr('[0]',[$activity_model->getElement('id')]));	
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
			if(!$g->model['document_url']  AND $g->model['related_document_id']) 
				$g->current_row_html['related_document_id'] = '';
				
			if(!$g->model['related_document_id'] && (strpos($g->model['activity'], 'Communicated') !== false) ) 
				$g->current_row_html['related_document_id'] = 'See Communication Detail';
			else
				if(!$g->model['related_document_id'])	
					$g->current_row_html['related_document_id'] = '';

			if(!$g->model['related_contact_id']) 
				$g->current_row_html['related_contact']= "";
		});

		$grid->addPaginator(50);
		$grid->addQuickSearch(['activity']);

	}
}
