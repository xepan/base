<?php

/**
* description: Marketplace is like center repository for all xEpans. 
* New components/applications can be added to xEpan system. That will be available to install 
* (Free,Shareware or Paid depending on author of application) for any Epan in xEpan Platform.
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
*/

namespace xepan\base;

class Model_Application extends \Model_Table{
	public $table='application';

	function init(){
		parent::init();

		$this->addField('name')->mandatory(true)->hint('Identification of xEpan Application');

		$this->hasMany('Epan_InstalledApplication',null,null,'Installations');

		$this->is([
				'name|unique|to_trim|required'
			]);
	}
}
