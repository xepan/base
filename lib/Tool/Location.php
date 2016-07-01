<?php

namespace xepan\base;

class Tool_Location extends \xepan\cms\View_Tool{
	public $options = [
			];	
	function init(){
		parent::init();


		$country = isset($this->app->country)?$this->app->country : $country = '';
		$state = isset($this->app->country)?$this->app->state : $state = '';

		if(!$country === ''){
			$this->template->trySet('c',$country);
			if(!$state ===''){
				$this->template->trySet('s',$state);
			}else{
				$this->template->trySet('s','Please Select State');
			}
		}else{			
			$this->template->trySet('c','Please Select Country And State');
		}

		$country_model = $this->add('xepan\base\Model_Country')->addCondition('status','Active');
		
		$cl = $this->add('CompleteLister',null,'country_lister',['view\tool\location\location','country_lister']);
		$cl->setModel($country_model);

		$cl->on('click',function($js,$data){
		$state_model = $this->add('xepan\base\Model_State');
		$state_model->addCondition('status','Active');
		$state_model->addCondition('Country_id',$data['id']);
		
		$sl = $this->add('CompleteLister',null,'state_lister',['view\tool\location\location','state_lister']);
		$sl->setModel($state_model);

		$js_new=[
				$this->js()->univ()->successMessage('Select State Now')
			];
		
		return $js_new;
		});
	}

	function defaultTemplate(){
		return['view\tool\location\location'];
	}
}