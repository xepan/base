<?php

/**
* description: Contains one or many, CRUD facility on data array 
* 
* @author : RK Sinha
* @email : info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Model_ConfigJsonModel extends \Model{
	public $fields = [];
	public $config_key;
	public $target_strategy = []; //STRATEGY_PLANNING_TARGET_AUDIENCE, STRATEGY_PLANNING_TARGET_LOCATION, STRATEGY_PLANNING_BUSINES_DESCRIPTION, STRATEGY_PLANNING_DIGITAL_PRESENCE, STRATEGY_PLANNING_COMETETORS
	public $strategy_config;
	function init(){
		parent::init();
		
		if(!count($this->fields))
			throw new \Exception("must define the fields");
		
		if(!$this->config_key)
			throw new \Exception("must define epan config key");

		foreach ($this->fields as $name => $type) {
			$field = $this->addField($name);
			if($type != "Line")
				$field->type($type);
		}

		$this->strategy_config = $this->app->epan->config;
		$this->target_strategy = json_decode($this->strategy_config->getConfig($this->config_key,'marketing')?:'{}',true);
		
		$this->setSource("Array",$this->target_strategy);

		$this->addHook('beforeSave',$this);
		$this->addHook('beforeDelete',$this);
	}

	function beforeSave(){
		$this->target_strategy[$this->id?:uniqid()] = $this->data;
		$this->strategy_config->setConfig($this->config_key,json_encode($this->target_strategy),'marketing');
	}

	function beforeDelete(){
		if(!$this->id)
			throw new \Exception("ConfigJsonModel id not defined", 1);
		
		unset($this->target_strategy[$this->id]);
		$this->strategy_config->setConfig($this->config_key,json_encode($this->target_strategy),'marketing');
	}

}