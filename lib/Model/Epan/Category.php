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

	}
}
