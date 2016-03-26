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

class Model_Epan_Configuration extends \xepan\base\Model_Table{
	public $table='mail_content';
	public $acl=false;

	function init(){
		parent::init();

		$this->hasOne('xepan\base\Epan');

		$this->addField('head');		
		$this->addField('value')->type('text')->display(['form'=>'xepan\base\RichText']);

		$this->addField('app');

	}

	function getConfig($head,$app=null){
		$config=$this->add('xepan\base\Model_Epan_Configuration');
		$config->addCondition('head',$head);
		if($app)
			$config->addCondition('app',$app);

		$config->tryLoadAny();
		return $config['value'];
	}

	function setConfig($head,$value,$app){
		$config=$this->add('xepan\base\Model_Epan_Configuration');
		$config->addCondition('head',$head);
		$config->addCondition('app',$app);
		$config->tryLoadAny();

		$config['value'] = $value;
		return $config->save();
	}
}
