<?php

namespace xepan\base;

class Tool_Location extends \xepan\cms\View_Tool{
	public $options = [
			];	
	function init(){
		parent::init();

		// $this->app->country = $this->add('xepan\base\Model_Country')->load(100); 
		// $this->app->state = $this->add('xepan\base\Model_State')->load(95);

		$country_model = $this->add('xepan\base\Model_Country')
							->addCondition('status','Active')
							->setOrder('name','asc');

		$state_model = $this->add('xepan\base\Model_State');
		$state_model->addExpression('country_status')->set($state_model->refSQL('country_id')->fieldQuery('status'));
		$state_model->addCondition('status','Active');
		$state_model->addCondition('country_status','Active');
		$state_model->setOrder('name','asc');

		$form = $this->add('Form',null,'form_layout');
		$form->setLayout(['view\tool\location\location','form_layout']);

		$country_field = $form->addField("xepan\base\DropDownNormal","country");
		$country_field->validate('required');

		$state_field = $form->addField("xepan\base\DropDownNormal","state");
		$state_field->validate('required');
		
		$country_field->setModel($country_model);
		$country_field->setEmptyText("Please Select Your Country");

		$selected_country_id = 0;
		if( isset($this->app->country) and ($this->app->country instanceof \xepan\base\Model_Country)){
			$country_field->set($this->app->country->id);
			$selected_country_id = $this->app->country->id;
		}

		if($_GET['location_country_id']){
			$selected_country_id = $_GET['location_country_id'];
		}


		if($selected_country_id){
			$state_model->addCondition('country_id',$selected_country_id);
			$state_field->setEmptyText("Please Select State");
		}else{
			$state_model->addCondition("country_id",-1);
			$state_field->setEmptyText("Please Select Country First");
		}

		$state_field->setModel($state_model);

		if(isset($this->app->state) and ($this->app->state instanceof \xepan\base\Model_State)){
			$selected_state_id = $this->app->state->id;
			if(!$_GET['location_country_id']){
				$state_field->set($selected_state_id);
			}

		}

		// Save Button
		$save_btn = $this->add('Button',null,"save_button");
		$save_btn->set("Update")
			->setAttr(array("type"=>"button"))
			->addClass('btn btn-primary')
			->setStyle('margin-top',0);
		$save_btn->js('click',$this->js()->find('.atk-form')->atk4_form("submitForm"));

		$country_field->js('change',$state_field->js()->reload(null,null,[$this->app->url(null,['cut_object'=>$state_field->name]),'location_country_id'=>$country_field->js()->val()]));

		// Form Submission
		if($form->isSubmitted()){

			$c_model = $this->add('xepan\base\Model_Country')->load($form['country']);
			$s_model = $this->add('xepan\base\Model_State')->load($form['state']);

			$this->app->memorize('xepan-customer-current-country',$c_model);
			$this->app->memorize('xepan-customer-current-state',$s_model);
			$this->app->country = $c_model;
			$this->app->state = $s_model;

			$this->app->redirect($this->app->url());
			// todo set the current country and state to app county and state
			// $form->js()->univ()->successMessage("Location Updated")->execute();
		}

		// if($this->app->country or $this->app->state)
		// $this->template->tryDel('location_fetcher_wrapper');

		// $this->js(true)->_library('navigator')->geolocation->getCurrentPosition($this->js(null,'$.ajax({url:})'))->_enclose());
	}

	function defaultTemplate(){
		return['view\tool\location\location'];
	}
}