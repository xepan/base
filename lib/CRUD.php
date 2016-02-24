<?php
namespace xepan\base;

class CRUD extends \CRUD{
	
	public $grid_class='xepan\base\Grid';
	public $action_page=null;
	public $pass_acl=false;

	protected function configureAdd($fields){
		if($this->action_page){
			$this->add_button->js('click')->univ()->location($this->api->url($this->action_page,['action'=>'add']));
		}else
			parent::configureAdd($fields);
	}

	protected function configureEdit($fields){
		if($this->action_page){
			$this->grid->on('click','.pb_edit')->univ()->location(
				[
					$this->api->url($this->action_page),
					[
						'action'=>'edit',
						$this->model->table.'_id'=>$this->js()->_selectorThis()->closest('tr')->data('id')
					]
				]
			);
		}else{
			// $this->grid->columns['delete']['template']= '<a class="table-link danger do-delete" href="#" data-id="'.$this->model->id.'"><span class="fa-stack"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-trash-o fa-stack-1x fa-inverse"></i></span></a>';
		 //    $this->grid->columns['edit']['template']= '<a class="table-link pb_edit" href="#" data-id="'.$this->model->id.'"><span class="fa-stack"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-pencil fa-stack-1x fa-inverse"></i></span></a>';
			parent::configureEdit($fields);
		}
	}

	function setModel($model,$grid_fields=null,$form_fields=null){

		$m = parent::setModel($model,$grid_fields,$form_fields);
		
		if((($m instanceof \xepan\base\Model_Document) || ($m instanceof \xepan\base\Model_Contact)) && !$this->pass_acl){
			$this->add('xepan\hr\Controller_ACL');
		}
		return $m;
	}

}