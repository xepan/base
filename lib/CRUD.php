<?php
namespace xepan\base;

class CRUD extends \CRUD{
	
	public $grid_class='xepan\base\Grid';
	public $action_page=null;

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
		}
		// parent::configureEdit($fields);
	}

}