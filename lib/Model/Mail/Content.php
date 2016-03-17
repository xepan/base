<?php

/**
* description: Contact Info stores various info for any contact. 
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class Model_Mail_Content extends Model_Table{
	public $table='mail_content';
	public $acl=false;

	function init(){
		parent::init();

		$this->hasOne('xepan\base\Epan');


		$this->addField('subject');		
		$this->addField('body')->type('text')->display(['form'=>'xepan\base\RichText']);

		$this->addField('type');

	}
}
