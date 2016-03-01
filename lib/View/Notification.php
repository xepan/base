<?php
namespace xepan\base;
class View_Notification extends \View{
	function init(){
		parent::init();

		$this->app->layout->template->trySet('notification_count','10');
		$this->app->layout->template->trySet('unread_notification','3');

	}
	function defaultTemplate(){
		return ['view/notification'];
	}
}