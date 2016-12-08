<?php

namespace xepan\base;

class page_logout extends \xepan\base\Page{
	public $title = "Logout Page";
	function init(){
		parent::init();

		$this->app->hook('logout_page',[$this]);
		$movement = $this->add('xepan\hr\Model_Employee_Movement');
		
		$form = $this->add('Form');
		$form->setModel($movement,['reason','narration']);
		$form->addSubmit('Logout')->addClass('btn btn-primary');


		if($form->isSubmitted()){
			if(!$form['reason'])
				$form->displayError('reason','Reason is mandatory');

			if(!$form['narration'])
				$form->displayError('narration','Narration is mandatory');
						
			$movement->addCondition('employee_id',$this->app->employee->id);
			$movement->addCondition('movement_at',$this->app->now);
			$movement->addCondition('direction','Out');
			$movement->addCondition('reason',$form['reason']);
			$movement->addCondition('narration',$form['narration']);
			$movement->save();

			$attan_m = $this->add("xepan\hr\Model_Employee_Attandance");
			$attan_m->addCondition('employee_id',$this->app->employee->id);
			$attan_m->addCondition('fdate',$this->app->today);
			$attan_m->setOrder('id','desc');
			$attan_m->tryLoadAny();

			if($movement['reason'] != 'Official Outing'){
				$attan_m['to_date'] = $this->app->now;
				$attan_m->save();
			}
									
			$this->app->hook('user_loggedout',[$this->app->auth->model,$this->app->employee]);
			$this->app->auth->logout();
			$this->app->redirect('/');
		}
	}
}