<?php

/**
* description: epan may have many Email Settings for sending and receiving enails.
* Since xEpan is primarily for cloud multiuser SaaS. Email settings are considered as base
* and included in Epan, not in any top layer Application.
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Model_Epan_EmailSetting extends \Model_Table{

	public $table='emailsetting';
	public $title_field='email_id';

	function init(){
		parent::init();
		// TODO : add all required fields for email
		
		$this->addField('email_id');
	}
}
