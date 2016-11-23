<?php

/**
* description: ATK Page
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

namespace xepan\base;

class page_test extends \Page {
	public $title='Page Title';

	function init(){
		parent::init();

		$btn = $this->add('Button')->set('PUSH');

		if($btn->isClicked()){
			$this->add('xepan\hr\Model_Activity')
				->pushToWebSocket([$this->app->employee->id],'Test Message');
			$this->js()->univ()->successMessage('Pushed')->execute();
		}


	}
}
