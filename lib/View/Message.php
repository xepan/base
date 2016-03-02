<?php
namespace xepan\base;
class View_Message extends \View{
	function init(){
		parent::init();

		$this->app->layout->template->trySet('message_count','20');

	}
	function defaultTemplate(){
		return ['view/message'];
	}
}