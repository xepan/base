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
			->setMessage('Populate country and states. Need help ! click on the help icon')
			->setHelpURL('#')
			->setAction('Click Here',$action,$isDone);
	}
}
