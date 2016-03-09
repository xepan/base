<?php

/**
* description: Epan Category are used only when installing xEpan as multi user based ERPaaS or Other
* Multi User based system.
* For Single use installation this is irrelevent. 
* @author : Gowrav Vishwakarma
* 
*/

namespace xepan\base;

class Model_Epan_Category extends \Model_Table{
	public $table='epan_category';

	function init(){
		parent::init();

		$this->addField('name')->mandatory(true)->hint('Identification for Category');
		$this->hasMany('Epan');
	
		$this->addHook('beforeSave',$this);
		$this->addHook('beforeDelete',$this);
	}

	function beforeSave($m){
		$epancat_old=$this->add('xepan\base\Model_Epan_Category');
		
		if($this->loaded())
			$epancat_old->addCondition('id','<>',$this->id);
		$epancat_old->tryLoadAny();

		if($epancat_old['name'] == $this['name'])
			throw $this->exception('Epan Name is Allready Exist');
	}


	function beforeDelete($m){
		$epan_count = $m->ref('Epan')->count()->getOne();
		
		if($epan_count)
			throw $this->exception('Cannot Delete,first delete Epan`s');	
	}
}
