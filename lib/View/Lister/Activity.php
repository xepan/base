<?php
namespace xepan\base;
class View_Lister_Activity extends \CompleteLister{
	function init(){
		parent::init();
	}
	function setModel($model){
		parent::setModel($model);
	}
	function defaultTemplate(){
		return ['view/activity/activities'];
	}
}