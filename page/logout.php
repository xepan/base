<?php

namespace xepan\base;

class page_logout extends \xepan\base\Page{
	public $title = "Logout Page";
	function init(){
		parent::init();

		$movement = $this->add('xepan\hr\Model_Employee_Movement');
		
		$form = $this->add('Form');
		$form->setModel($movement,['reason','narration']);
		$form->addSubmit('Logout')->addClass('btn btn-primary');

		if($form->isSubmitted()){
			$movement->addCondition('employee_id',$this->app->employee->id);
			$movement->addCondition('time',$this->app->now);
			$movement->addCondition('type','Attandance');
			$movement->addCondition('direction','Out');
			$movement->addCondition('reason',$form['reason']);
			$movement->addCondition('narration',$form['narration']);
			$movement->save();

			$this->app->hook('user_loggedout',[$this->app->auth->model,$this->app->employee]);
			$this->app->auth->logout();
			$this->app->redirect('/');
		}
	}
}