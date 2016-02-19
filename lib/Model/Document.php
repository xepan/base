<?php

/**
* description: Document is a global model for almost all documents in xEpan platform.
* Main purpose of document model/table is to give a system wide unique id for all documents spreaded 
* in various tables.
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Model_Document extends \xepan\base\Model_Table{
	
	public $table='document';

	function init(){
		parent::init();
		
		$this->addField('type');

	}
}
