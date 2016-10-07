<?php

namespace xepan\base;

class View_EasySetupWizard extends \View{
	
	function init(){
		parent::init();
		
		if($_GET[$this->name.'_set_countries']){
			$this->api->db->dsql()->expr(file_get_contents(realpath(getcwd().'/vendor/xepan/base/countriesstates.sql')))->execute();
			$this->js(true)->reload();
		}

		$isDone = false;
		
		$action = $this->js()->reload([$this->name.'_set_countries'=>1]);

		if($this->add('xepan\base\Model_Country')->count()->getOne() > 0){
			$isDone = true;
			$action = $this->js()->univ()->dialogOK("Already have Data",' You already have countries populated, visit page ? <a href="'. $this->app->url('xepan_communication_general_countrystate')->getURL().'"> click here to go </a>');
		}

		$country_view = $this->add('xepan\base\View_Wizard_Step');

		$country_view->setAddOn('Application - Base')
			->setTitle('Country & State database not populated yet')
			->setMessage('Populate country and states.')
			->setHelpMessage('Need help ! click on the help icon')
			->setHelpURL('#')
			->setAction('Click Here',$action,$isDone);

		if($_GET[$this->name.'_time_zone']){
			
			$this->js(true)->univ()->frameURL("Time Zone",$this->app->url('xepan_communication_generalsetting'));
		}

		$isDone = false;
		
		$action = $this->js()->reload([$this->name.'_time_zone'=>1]);
		$misc_m = $this->add('xepan\base\Model_ConfigJsonModel',
		[
			'fields'=>[
						'time_zone'=>'DropDown'
						],
				'config_key'=>'Miscellaneous_Technical_Settings',
				'application'=>'base'
		]);
		$misc_m->tryLoadAny();	

		if($misc_m['time_zone']){
			$isDone = true;
			$action = $this->js()->univ()->dialogOK("Already have Data",' You already have updated the time zone, visit page ? <a href="'. $this->app->url('xepan_communication_generalsetting')->getURL().'"> click here to go </a>');
		}	

		$time_zone_view = $this->add('xepan\base\View_Wizard_Step');

		$time_zone_view->setAddOn('Application - Base')
			->setTitle('Update Your Time Zone')
			->setMessage('Update your time zone accoding your country.')
			->setHelpMessage('Need help ! click on the help icon')
			->setHelpURL('#')
			->setAction('Click Here',$action,$isDone);
	}
}
