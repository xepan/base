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

		if($this->communication_type){
			$model_name = 'Communication';
			$model = $this->add('xepan\communication\Model_Communication');
			$model->addCondition('communication_type',$this->communication_type);
			$related_contact = 'to_id';
			$contact_id = 'from_id';
			$columns = ['title','from','to','created_at'];
			$grid_template = ['view\activity\communication-activities'];
		}else{
			$model_name = 'activity';
			$model = $this->add('xepan\base\Model_Activity');
			$related_contact = 'related_contact_id';
			$contact_id = 'contact_id';
			$columns = ['activity','contact','related_document_id','related_contact','callback_date'];
			$grid_template = ['view\activity\activities'];
		}

		$model->addExpression('contact_type',$model->refSQL($related_contact)->fieldQuery('type'));
		
		$model->addExpression('department')->set(function($m,$q)use($contact_id){
			$employee = $this->add('xepan\hr\Model_Employee');
			$employee->addCondition('id',$m->getField($contact_id));
			$employee->setLimit(1);
			return $employee->fieldQuery('department_id');
		});

		$model->addExpression('post')->set(function($m,$q)use($contact_id){
			$employee = $this->add('xepan\hr\Model_Employee');
			$employee->addCondition('id',$m->getElement($contact_id));
			$employee->setLimit(1);
			return $employee->fieldQuery('post_id');
		});

		$model->addCondition('post',array_unique($this->descendants));

		if($this->self_activity == 'true'){											
			$model->addCondition($contact_id,'<>',$this->app->employee->id);			
		}
		if($this->from_date){
			$model->addCondition('created_at','>=',$this->from_date);			
		}
		if($this->to_date){
			$model->addCondition('created_at','<',$this->app->nextDate($this->to_date));
		}
		if($this->contact_id){
			$model->addCondition($contact_id,$this->contact_id);
		}
		if($this->related_person_id){
			$model->addCondition($related_contact,$this->related_person_id);
		}
		if($this->department_id){
			$model->addCondition('department',$this->department_id);
		}

		$grid = $this->add('xepan\base\Grid',null,null,$grid_template);
		$grid->setModel($model,$columns);
		
		$grid->addHook('formatRow',function($g)use($related_contact){												
			switch($g->model['contact_type']){
				case 'Contact':
					$contact_url='xepan_marketing_leaddetails'.'&contact_id='.$g->model[$related_contact];
					break;
				case 'Lead':
					$contact_url='xepan_marketing_leaddetails'.'&contact_id='.$g->model[$related_contact];
					break;
				case 'Customer':
					$contact_url='xepan_commerce_customerdetail'.'&contact_id='.$g->model[$related_contact];
					break;
				case 'Supplier':
					$contact_url='xepan_commerce_supplierdetail'.'&contact_id='.$g->model[$related_contact];
					break;
				case 'Employee':
					$contact_url='xepan_hr_employeedetail'.'&contact_id='.$g->model[$related_contact];
					break;
				case 'OutsourceParty':
					$contact_url='xepan_production_outsourcepartiesdetails'.'&contact_id='.$g->model[$related_contact];
					break;
				default:
					$contact_url='xepan_base_contactdetail';
			}			
			$g->current_row['contact_url']= $contact_url;
		});

		$grid->addHook('formatRow',function($g)use($model_name){
			if($model_name === 'Communication'){
					$g->current_row_html['document_url']= 'xepan_communication_viewer&comm_id='.$g->model['id'];
			}else{
				if(!$g->model['document_url']  AND $g->model['related_document_id']) 
					$g->current_row_html['related_document_id'] = '';
					
				if(!$g->model['related_document_id'] && (strpos($g->model['activity'], 'Communicated') !== false) ) 
					$g->current_row_html['related_document_id'] = 'See Communication Detail';
				else
					if(!$g->model['related_document_id'])	
						$g->current_row_html['related_document_id'] = '';

				if(!$g->model['related_contact_id']) 
					$g->current_row_html['related_contact']= "";
			}
				
		});

		$grid->addPaginator(50);
		$grid->addQuickSearch(['activity']);

	}
}
