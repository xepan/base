<?php

/**
* description: Model Documet Attachment
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;


class Model_Document_Attachment extends Model_Table{
	
	public $table='attachemt';
	public $acl = false;

	function init(){
		parent::init();
		
		$this->hasOne('xepan\base\Document','document_id');
		$this->add('filestore\File','file_id');
	}
}
