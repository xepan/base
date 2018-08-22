<?php

namespace xepan\base;

class Tool_KeepAlive extends \xepan\cms\View_Tool{
	public $options = [
				'timeout_seconds'=>10,
			];	

	function init(){
		parent::init();
		$this->setStyle('display','none');
		if(!$this->app->isAjaxOutput()){
			$this->js(true)->univ()->setInterval($this->js()->reload()->_enclose()),$this->options['timeout_seconds']);
		}

	}
}