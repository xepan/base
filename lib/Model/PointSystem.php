<?php

namespace xepan\base;

/**
* 
*/
class Model_PointSystem extends \xepan\base\Model_Table
{
	public $table='point_system';
	
	function init()
	{
		parent::init();

		$this->hasOne('xepan\base\Rules','rule_id');
		$this->hasOne('xepan\base\RulesOption','rule_option_id');
		$this->addField('contact_id');
		$this->addField('score')->defaultValue(0);
		$this->addField('landing_campaign_id');
		$this->addField('landing_content_id');
	}
}