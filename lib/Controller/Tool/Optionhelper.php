<?php

namespace xepan\base;

class Controller_Tool_Optionhelper extends \AbstractController {

	public $model=null;

	function init(){
		parent::init();

		$this->options = $this->owner->options;
		
		if(is_string($this->model))
			throw $this->exception("Please specify model object not model string")
						->addMoreInfo('model_provided',$this->model);

		if(!$this->model)
			$this->model = $this->owner->model;


		if($this->model === null) throw $this->exception("Please specify model");

		// Manage show options
		foreach ($this->options as $opt=>$value) {
			$opt = strtolower($opt);
			if(strpos($opt, "show_")!==false){
				$opt = str_replace('show_', '', $opt);				
				if($value === false || $value ==='0' || $value === 'false' || $value ===null || $value ==='null' || $value==='undefined'){
					$this->owner->template->tryDel($opt.'_wrapper');
				} 
			}
		}

		foreach ($this->options as $opt => $value) {
			if($this->model->hasMethod('addToolCondition_'.$opt)){
				$this->model->{'addToolCondition_'.$opt}($value, $this->owner);
			}else{
				$elm = $this->model->hasElement($opt);
				if($elm && $elm instanceof \Field && $value !=='%'){
					$this->model->addCondition($opt,$value);
				}
			}
		}
	}
}